<?php
/**
 *  Database information here
 *  -------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Request_Quote_Database' ) && class_exists( 'SDWeddingDirectory_Config' ) ){

    /**
     *  Database information here
     *  -------------------------
     */
    class SDWeddingDirectory_Request_Quote_Database extends SDWeddingDirectory_Config{

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

        }

        /**
         *  Couple Already Submited Review ?
         *  --------------------------------
         */
        public static function couple_already_submit_request( $args = [] ){

            if( parent:: _is_array( $args ) ){

                extract( $args );

                global $wp_query, $post;

                $_counter  =    absint( '0' );

                $meta_query   = [];

                /**
                 *  Venue ID
                 *  ----------
                 */
                $meta_query[] = array(

                    'key'       => esc_attr('venue_id'),
                    'type'      => 'numeric',
                    'compare'   => '=',
                    'value'     => absint( $venue_id ),
                );
              
                /**
                 *  Couple ID
                 *  ---------
                 */
                $meta_query[] = array(

                    'key'       => esc_attr('couple_id'),
                    'type'      => 'numeric',
                    'compare'   => '=',
                    'value'     => absint( $couple_id ),
                );

                /**
                 *  Have POST ?
                 *  -----------
                 */
                $_couple_have_venue_request_query  =   new WP_Query( array(

                    'post_type'             =>  esc_attr( 'venue-request' ),

                    'post_status'           =>  array( 'publish', 'pending' ),

                    'posts_per_page'        =>  -1,

                    'meta_query'            =>  array(

                            'relation'  =>  'AND',

                            $meta_query,
                    ),

                ) );

                /**
                 *  Get Number of Post ( counter )
                 *  ------------------------------
                 */
                $_count_post    =   absint( $_couple_have_venue_request_query->found_posts );

                if( isset( $_couple_have_venue_request_query ) ){

                    /**
                     *  Reset this query
                     *  ----------------
                     */
                    wp_reset_postdata();
                }

                /**
                 *  Have Review at least 1 ? ( Couple already publish review ? )
                 *  ------------------------------------------------------------
                 */
                if( absint( $_count_post ) >= absint( '1' ) ){

                    return true;

                }else{

                    return false;
                }
            }
        }

        /**
         *  Grid
         *  ----
         */
        public static function grid( $atts = '' ){

            if( empty( $atts ) ) return;

            $_column_ = '';

            if( $atts === 'col-md-3' || $atts === '4' ){ 

                return 'col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12';

            }elseif( $atts == 'col-md-4' || $atts == '3' ){   

                return 'col-xl-4 col-lg-6 col-md-6 col-12 mb-0 mb-0';

            }elseif( $atts == 'col-md-6' || $atts == '2' ){   

                return 'col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12'; 

            }elseif( $atts == 'col-md-2' || $atts == '6' ){ 

                return 'col-xl-2 col-lg-6 col-md-6 col-sm-4 col-12'; 

            }elseif( $atts == 'col-md-12' || $atts == '1' ){ 

                return  'col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12'; 

            }else{ 

                return 'col-lg-4 col-md-4 col-sm-6 col-12';  
            }
        }

        /**
         *  Tab content repetable fields
         *  ----------------------------
         */
        public static function request_quote_tab_content_fields( $args = [] ){

            if( parent:: _is_array( $args ) ){

                $default    =   array(

                    'grid'      =>  absint( '3' ),

                    'label'     =>  '',

                    'value'     =>  '',

                    'class'     =>  ''
                );

                $args   =   wp_parse_args( $args, $default );

                extract( $args );

                if( isset( $value ) && $value !== '' && ! empty( $value )  ){

                    return

                    sprintf(   '<div class="%1$s mb-0">
                                    <div class="wedding-info-details border-bottom %4$s"><small>%2$s</small> %3$s </div>
                                </div>',

                                /**
                                 *  1. Grid
                                 *  -------
                                 */
                                self:: grid( absint( $grid ) ),

                                /**
                                 *  2. Label
                                 *  --------
                                 */
                                esc_attr( $label ),

                                /**
                                 *  3. Value
                                 *  --------
                                 */
                                esc_attr( $value ),

                                /**
                                 *  4. Have Class ?
                                 *  ---------------
                                 */
                                sanitize_html_class( $class )
                    );
                }
            }
        }

        /**
         *  Contact Via ?
         *  -------------
         */
        public static function contact_via( $_contact_via = 0 ){

            /**
             *  Is Valid Venue ID ?
             *  ---------------------
             */
            if( parent:: _have_data( $_contact_via ) ){

                if( $_contact_via == absint( '1' ) ){

                    /**
                     *  1. Translation Ready String
                     *  ---------------------------
                     */
                    return  esc_attr__( 'Call', 'sdweddingdirectory-request-quote' );

                }elseif( $_contact_via == absint( '2' ) ){

                    /**
                     *  1. Translation Ready String
                     *  ---------------------------
                     */
                    return  esc_attr__( 'Email', 'sdweddingdirectory-request-quote' );
                
                }elseif( $_contact_via == absint( '3' ) ){

                    /**
                     *  1. Translation Ready String
                     *  ---------------------------
                     */
                    return  esc_attr__( 'Video Call', 'sdweddingdirectory-request-quote' );
                }
            }
        }

        /**
         *  Request Tab
         *  -----------
         */
        public static function get_request_tab_data( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( wp_parse_args( $args, [

                    /**
                     *  In Args
                     *  -------
                     */
                    'venue_id'        =>      absint( '0' ),

                    'vendor_id'         =>      absint( '0' ),

                    'venue_name'      =>      '',

                    'unique_id'         =>      esc_attr( parent:: _rand() ),

                    'get_data'          =>      '',

                    /**
                     *  Var
                     *  ---
                     */
                    'meta_query'        =>      [],

                    'tab'               =>      absint( '0' ),

                    'content'           =>      absint( '0' ),

                    'handler'           =>      ''

                ] ) );

                /**
                 *  Have Venue ID ?
                 *  -----------------
                 */
                if( ! empty( $venue_id ) ){

                    $meta_query[]       =   array(

                                                'key'       =>  esc_attr( 'venue_id' ),

                                                'type'      =>  esc_attr( 'numeric' ),

                                                'compare'   =>  esc_attr( '=' ),

                                                'value'     =>  absint( $venue_id ),
                                            );
                }

                /**
                 *  Have Vendor ID ?
                 *  ----------------
                 */
                if( ! empty( $vendor_id ) ){

                    $meta_query[]       =   array(

                                                'key'       =>  esc_attr( 'vendor_id' ),

                                                'type'      =>  esc_attr( 'numeric' ),

                                                'compare'   =>  esc_attr( '=' ),

                                                'value'     =>  absint( $vendor_id ),
                                            );
                }

                /**
                 *  Have POST ?
                 *  -----------
                 */
                $query              =   new WP_Query( array(

                                            'post_type'         =>  esc_attr( 'venue-request' ),

                                            'post_status'       =>  array( 'publish' ),

                                            'meta_query'        =>  array(

                                                                        'relation'  => 'AND',

                                                                        $meta_query,
                                                                    ),
                                        ) );

                /**
                 *  Have Post ?
                 *  -----------
                 */
                if ( $query->have_posts() ){

                    /**
                     *  In Loop
                     *  -------
                     */
                    while ( $query->have_posts() ) {    $query->the_post();

                        /**
                         *  Post ID
                         *  -------
                         */
                        $post_id            =       absint( get_the_ID() );

                        /**
                         *  Extract Args
                         *  ------------
                         */
                        extract( [

                            'couple_id'         =>      absint( get_post_meta( absint( $post_id ), sanitize_key( 'couple_id' ), true ) ),

                            'vendor_id'         =>      absint( get_post_meta( absint( $post_id ), sanitize_key( 'vendor_id' ), true ) ),

                            'venue_id'        =>      absint( get_post_meta( absint( $post_id ), sanitize_key( 'venue_id' ), true ) ),

                        ] );

                        extract( [
                        
                            'couple_img'        =>      apply_filters( 'sdweddingdirectory/user-profile-image', [ 'post_id'  =>   absint( $couple_id )  ] ),

                            'vendor_img'        =>      apply_filters( 'sdweddingdirectory/user-profile-image', [ 'post_id'  =>   absint( $vendor_id )  ] ),

                            'couple_name'       =>      sprintf( '%1$s %2$s', 

                                                            get_post_meta( absint( $couple_id ), sanitize_key( 'first_name' ), true ),

                                                            get_post_meta( absint( $couple_id ), sanitize_key( 'last_name' ), true )
                                                        ),

                            'vendor_name'       =>      sprintf( '%1$s %2$s',

                                                            get_post_meta( absint( $vendor_id ), sanitize_key( 'first_name' ), true ),

                                                            get_post_meta( absint( $vendor_id ), sanitize_key( 'last_name' ), true )
                                                        ),

                            'couple_email'      =>      get_post_meta( absint( $couple_id ), sanitize_key( 'user_email' ), true ),

                            'couple_contact'    =>      get_post_meta( absint( $post_id ), sanitize_key( 'request_quote_contact' ), true ),

                            'wedding_date'      =>      get_post_meta( absint( $couple_id ), sanitize_key( 'wedding_date' ), true  ),

                            'couple_budget'     =>      sdweddingdirectory_pricing_possition(

                                                            esc_attr( get_post_meta( absint( $post_id ), sanitize_key( 'request_quote_budget' ), true  ) ),

                                                            ' ',    ' '
                                                        ),

                            'contact_via'       =>      self:: contact_via( absint(

                                                            get_post_meta( absint( $post_id ), sanitize_key( 'request_quote_contact_option' ), true )

                                                        ) ),

                            'couple_guest'      =>      esc_attr( get_post_meta( absint( $post_id ), sanitize_key( 'request_quote_guest' ), true ) ),

                        ] );

                        /**
                         *  Showing Tabs
                         *  ------------
                         */
                        if( $get_data == esc_attr( 'tabs' ) ){

                            $_tab_lead_status = get_post_meta( $post_id, sanitize_key( 'sdwd_lead_status' ), true );

                            if( empty( $_tab_lead_status ) ){
                                $_tab_lead_status = 'new';
                            }

                            /**
                             *  Tabs Collection
                             *  ---------------
                             */
                            $handler .=     sprintf(    '<a class="nav-link %4$s" data-bs-toggle="pill"  role="tab"

                                                            id="%5$s-tab" href="#%5$s" aria-controls="%5$s" aria-selected="%6$s" data-lead-status="%7$s">

                                                            <div class="reviews-media">

                                                                <div class="media">

                                                                    <img class="thumb" src="%1$s" alt="%2$s" />

                                                                    <div class="media-body">

                                                                        <div class="heading-wrap g-0">

                                                                            <div class="heading">

                                                                                <h4 class="mb-0">%2$s <span class="sdwd-status-dot sdwd-status-%7$s"></span></h4>

                                                                                <small>%3$s</small>

                                                                            </div>

                                                                        </div>

                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </a>',

                                                        /**
                                                         *  1. Get Couple Profile Picture
                                                         *  -----------------------------
                                                         */
                                                        esc_url( $couple_img ),

                                                        /**
                                                         *  2. Couple Name
                                                         *  --------------
                                                         */
                                                        esc_attr( $couple_name ),

                                                        /**
                                                         *  3. Mariage Date
                                                         *  ---------------
                                                         */
                                                        ! empty( $wedding_date )

                                                        ?   sprintf( esc_attr__( 'Married on %1$s', 'sdweddingdirectory-request-quote' ),

                                                                /**
                                                                 *  1. Couple Wedding Date
                                                                 *  ----------------------
                                                                 */
                                                                apply_filters( 'sdweddingdirectory/date-format', [

                                                                    'date'      =>      esc_attr( $wedding_date )

                                                                ] )
                                                            )

                                                        :   '',

                                                        /**
                                                         *  4. Active Tab
                                                         *  -------------
                                                         */
                                                        $tab == absint( '0' )   ?   sanitize_html_class( 'active' )  :  '',

                                                        /**
                                                         *  5. Get Unique ID
                                                         *  ----------------
                                                         */
                                                        esc_attr( $unique_id ),

                                                        /**
                                                         *  6. Is Active ?
                                                         *  --------------
                                                         */
                                                        $tab == absint( '0' )   ?   sanitize_html_class( 'true' )   :   sanitize_html_class( 'false' ),

                                                        /**
                                                         *  7. Lead Status
                                                         *  --------------
                                                         */
                                                        esc_attr( $_tab_lead_status )
                            );
                        }

                        /**
                         *  Tab Content
                         *  -----------
                         */
                        if( $get_data == esc_attr( 'tabs_content' ) ){

                            /**
                             *  Removed Button
                             *  --------------
                             */
                            $_remove_btn    =

                            sprintf(   '<div class="d-grid">

                                            <a href="javascript:" class="btn btn-default btn-block action-links sdweddingdirectory_remove_request_quote" 

                                                data-request-security="%1$s" 

                                                data-venue-security="%3$s" 

                                                data-request-id="%2$s"

                                                data-venue-id="%4$s"

                                                data-alert="%5$s">%6$s</a>

                                        </div>',

                                        /**
                                         *  1. SDWeddingDirectory - Security
                                         *  ------------------------
                                         */
                                        wp_create_nonce( 'sdweddingdirectory_request_quote_unique_id-' . $post_id ),

                                        /**
                                         *  2. SDWeddingDirectory - Request Quote Unique ID
                                         *  ---------------------------------------
                                         */
                                        absint( $post_id ),

                                        /**
                                         *  3. SDWeddingDirectory - Venue ID Security
                                         *  -----------------------------------
                                         */
                                        wp_create_nonce( 'sdweddingdirectory_venue_unique_id-' . $venue_id ),

                                        /**
                                         *  4. SDWeddingDirectory - Request Quote Unique ID
                                         *  ---------------------------------------
                                         */
                                        absint( $venue_id ),

                                        /**
                                         *  5. SDWeddingDirectory - Remove Request Quote Alert
                                         *  ------------------------------------------
                                         */
                                        esc_attr__( 'Are you sure you want to remove this venue request ? It will never recover again. Are you confirm ?', 'sdweddingdirectory-request-quote' ),

                                        /**
                                         *  6. Translation Ready String
                                         *  ---------------------------
                                         */
                                        esc_attr__( 'Delete Request', 'sdweddingdirectory-request-quote' )
                            );

                            /**
                             *  Get Response Div Structure ( Approved / Reject )
                             *  ------------------------------------------------
                             */
                            $_request_status    =

                            sprintf(   '<div class="row">

                                            <div class="col-sm-12 mb-4">

                                                <div class="making-venue-response border">

                                                    <strong>%1$s</strong>

                                                    <div>

                                                        <div class="form-check form-check-inline">

                                                            <input autocomplete="off" type="radio" id="approved_%2$s" name="request_status_%2$s" class="approved_request form-check-input">

                                                            <label class="form-check-label" for="approved_%2$s">%3$s</label>

                                                        </div>

                                                        <div class="form-check form-check-inline">

                                                            <input autocomplete="off" type="radio" id="reject_%2$s" name="request_status_%2$s" class="reject_request form-check-input">

                                                            <label class="form-check-label" for="reject_%2$s">%4$s</label>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                            <div class="col-12">%5$s</div>

                                        </div>',

                                        /**
                                         *  1. Translation Ready String
                                         *  ---------------------------
                                         */
                                        esc_attr__( 'Make Your Response', 'sdweddingdirectory-request-quote' ),

                                        /**
                                         *  2. Random ID
                                         *  ------------
                                         */
                                        esc_attr( parent:: _rand() ),

                                        /**
                                         *  3. Translation Ready String
                                         *  ---------------------------
                                         */
                                        esc_attr__( 'Approve Venue', 'sdweddingdirectory-request-quote' ),

                                        /**
                                         *  4. Translation Ready String
                                         *  ---------------------------
                                         */
                                        esc_attr__( 'Reject Venue', 'sdweddingdirectory-request-quote' ),

                                        /**
                                         *  5. Removed Request
                                         *  ------------------
                                         */
                                        $_remove_btn
                            );

                            /**
                             *  Request Data 
                             *  ------------
                             */
                            $_table_data    =

                            sprintf(   '<div class="reviews-media">

                                            <div class="row">
                                                
                                                %1$s <!-- Get Request User Name -->

                                                %2$s <!-- Get Request User Wedding Date -->

                                                %3$s <!-- Get Request User Budget -->

                                                %4$s <!-- Get Request User Email -->

                                                %5$s <!-- Get Request User Phone -->

                                                %6$s <!-- Number of Guest -->

                                                %7$s <!-- Preferred contact method -->

                                                %8$s <!-- Message -->

                                            </div>

                                            %9$s <!-- Request Status ( Approved / Reject ) -->

                                        </div>',

                                        /**
                                         *  1. Get Request User Name
                                         *  ------------------------
                                         */
                                        self:: request_quote_tab_content_fields( array(

                                            /**
                                             *  1. Translation String
                                             *  ---------------------
                                             */
                                            'label'     =>      esc_attr__( 'Name', 'sdweddingdirectory-request-quote' ),

                                            /**
                                             *  2. Value
                                             *  --------
                                             */
                                            'value'     =>      esc_attr( $couple_name )
                                        ) ),

                                        /**
                                         *  2. Get Request User Wedding Date
                                         *  --------------------------------
                                         */
                                        self:: request_quote_tab_content_fields( array(

                                            /**
                                             *  1. Translation String
                                             *  ---------------------
                                             */
                                            'label'     =>      esc_attr__( 'Wedding Date', 'sdweddingdirectory-request-quote' ),

                                            /**
                                             *  2. Value
                                             *  --------
                                             */
                                            'value'     =>      apply_filters( 'sdweddingdirectory/date-format', [

                                                                    'date'      =>      esc_attr( $wedding_date )

                                                                ] )
                                        ) ),

                                        /**
                                         *  3. Get Request User Budget
                                         *  --------------------------
                                         */
                                        self:: request_quote_tab_content_fields( array(

                                            /**
                                             *  1. Translation String
                                             *  ---------------------
                                             */
                                            'label'     =>      esc_attr__( 'Budget', 'sdweddingdirectory-request-quote' ),

                                            /**
                                             *  2. Value
                                             *  --------
                                             */
                                            'value'     =>      esc_attr( $couple_budget ),

                                            /**
                                             *  3. Have Extra Class
                                             *  -------------------
                                             */
                                            'class'     =>      sanitize_html_class( 'budget-highlight' )
                                        ) ),

                                        /**
                                         *  4. Get Request User Email
                                         *  --------------------------
                                         */
                                        self:: request_quote_tab_content_fields( array(

                                            /**
                                             *  1. Translation String
                                             *  ---------------------
                                             */
                                            'label'     =>      esc_attr__( 'Email', 'sdweddingdirectory-request-quote' ),

                                            /**
                                             *  2. Value
                                             *  --------
                                             */
                                            'value'     =>      sanitize_email( $couple_email )
                                        ) ),

                                        /**
                                         *  5. Get Request User Phone
                                         *  --------------------------
                                         */
                                        self:: request_quote_tab_content_fields( array(

                                            /**
                                             *  1. Translation String
                                             *  ---------------------
                                             */
                                            'label'     =>      esc_attr__( 'Phone', 'sdweddingdirectory-request-quote' ),

                                            /**
                                             *  2. Value
                                             *  --------
                                             */
                                            'value'     =>      esc_attr( $couple_contact )
                                        ) ),

                                        /**
                                         *  6. Get Request User Have Wedding Guest
                                         *  --------------------------------------
                                         */
                                        self:: request_quote_tab_content_fields( array(

                                            /**
                                             *  1. Translation String
                                             *  ---------------------
                                             */
                                            'label'     =>      esc_attr__( 'No of Guest', 'sdweddingdirectory-request-quote' ),

                                            /**
                                             *  2. Value
                                             *  --------
                                             */
                                            'value'     =>      esc_attr( $couple_guest )
                                        ) ),

                                        /**
                                         *  8. Contact Via
                                         *  --------------
                                         */
                                        self:: request_quote_tab_content_fields( array(

                                            /**
                                             *  1. Translation String
                                             *  ---------------------
                                             */
                                            'label'     =>      esc_attr__( 'Preferred contact method', 'sdweddingdirectory-request-quote' ),

                                            /**
                                             *  2. Value
                                             *  --------
                                             */
                                            'value'     =>      esc_attr( $contact_via ),

                                            /**
                                             *  3. Grid
                                             *  -------
                                             */
                                            'grid'      =>      absint( '1' )
                                        ) ),

                                        /**
                                         *  9. Get Request User Message
                                         *  ---------------------------
                                         */
                                        self:: request_quote_tab_content_fields( array(

                                            /**
                                             *  1. Translation String
                                             *  ---------------------
                                             */
                                            'label'     =>      esc_attr__( 'Message', 'sdweddingdirectory-request-quote' ),

                                            /**
                                             *  2. Value
                                             *  --------
                                             */
                                            'value'     =>      esc_textarea( 

                                                                    get_post_meta( 

                                                                        absint( $post_id ), 

                                                                        sanitize_key( 'request_quote_message' ), 

                                                                        true
                                                                    )
                                                                ),

                                            /**
                                             *  3. Grid
                                             *  -------
                                             */
                                            'grid'      =>      absint( '1' )

                                        ) ),

                                        /**
                                         *  10. Random value
                                         *  ---------------
                                         */
                                        ( $_request_status !== '' && FALSE )

                                        ?   $_request_status

                                        :   ''
                            );

                            /**
                             *  Lead Management Panel
                             *  ---------------------
                             */
                            $_lead_panel = self:: lead_management_panel( $post_id );

                            /**
                             *  Removed Request Button + Lead Panel
                             *  ------------------------------------
                             */
                            $handler .=

                            sprintf(    '<div class="tab-pane fade %1$s" id="%2$s" role="tabpanel" aria-labelledby="%2$s-tab">%3$s%4$s</div>',

                                /**
                                 *  1. Tab Content Active
                                 *  ---------------------
                                 */
                                $content == absint( '0' )   ?   esc_attr( 'show active' )  :   '',

                                /**
                                 *  2. Get Unique ID
                                 *  ----------------
                                 */
                                esc_attr( $unique_id ),

                                /**
                                 *  3. Get The Review Title
                                 *  -----------------------
                                 */
                                !   empty( $_table_data )   ?   $_table_data    :   '',

                                /**
                                 *  4. Lead Management Panel
                                 *  ------------------------
                                 */
                                $_lead_panel
                            );
                        }

                        /**
                         *  Counter
                         *  -------
                         */
                        $tab++;     $content++;
                    }

                    /**
                     *  Reset Filter
                     *  ------------
                     */
                    if(  isset( $query ) && $query !== '' ){

                        wp_reset_postdata();
                    }
                }

                /**
                 *  Return Data
                 *  -----------
                 */
                return          $handler;
            }
        }

        /**
         *  Find out request post for current login vendor
         *  ----------------------------------------------
         */
        public static function get_request_quote_counter(){

            global $post, $wp_query;

            $meta_query     =   [];

            /**
             *  Find out Total Post for vendor
             *  ------------------------------
             */
            $meta_query[]   =   array(

                'key'       =>  esc_attr( 'vendor_id' ),

                'type'      =>  esc_attr( 'numeric' ),

                'compare'   =>  esc_attr( '=' ),

                'value'     =>  absint( parent:: post_id() )
            );

            /**
             *  @var Request Quote :  WP_Query
             *  ------------------------------
             */
            $_request_quote_query   =   new WP_Query( array(

                'post_type'         =>  esc_attr( 'venue-request' ),

                'post_status'       =>  esc_attr( 'publish' ), 

                'posts_per_page'    =>  -1,

                'meta_query'        =>  array(

                    'relation'      =>  'AND',

                    $meta_query,
                )

            ) );

            if( $_request_quote_query->found_posts >= absint( '1' ) ){

                return  absint( $_request_quote_query->found_posts );

            }else{

                return  absint( '0' );
            }
        }


        /**
         *  Contact Via Key to get value
         *  ----------------------------
         */
        public static function vendor_contact_couple_via( $args = '1' ){

            /**
             *  1. Is Video Call ?
             *  ------------------
             */
            if( $args == absint( '3' ) ){

                return  esc_attr__( 'Video Call', 'sdweddingdirectory-request-quote' );
            }

            /**
             *  2. Is Email ?
             *  -------------
             */
            elseif( $args == absint( '2' ) ){

                return  esc_attr__( 'Email', 'sdweddingdirectory-request-quote' );
            }

            /**
             *  3. Default : Call
             *  -----------------
             */
            else{

                return  esc_attr__( 'Call', 'sdweddingdirectory-request-quote' );
            }
        }


        /**
         *  Lead Status Options
         *  -------------------
         */
        public static function lead_status_options(){

            return [
                'new'               => esc_html__( 'New', 'sdweddingdirectory' ),
                'attempted_contact' => esc_html__( 'Attempted Contact', 'sdweddingdirectory' ),
                'replied'           => esc_html__( 'Replied', 'sdweddingdirectory' ),
                'appointment_set'   => esc_html__( 'Appointment Set', 'sdweddingdirectory' ),
                'researching'       => esc_html__( 'Researching Options', 'sdweddingdirectory' ),
                'follow_up'         => esc_html__( 'Follow Up Later', 'sdweddingdirectory' ),
                'no_response'       => esc_html__( 'No Response', 'sdweddingdirectory' ),
                'not_interested'    => esc_html__( 'Not Interested', 'sdweddingdirectory' ),
                'lost'              => esc_html__( 'Lost to Competitor', 'sdweddingdirectory' ),
                'booked'            => esc_html__( 'Booked', 'sdweddingdirectory' ),
                'invalid'           => esc_html__( 'Invalid/Spam', 'sdweddingdirectory' ),
            ];
        }

        /**
         *  Activity Tag Options
         *  --------------------
         */
        public static function activity_tag_options(){

            return [
                'sent_email'        => esc_html__( 'Sent Email', 'sdweddingdirectory' ),
                'left_voicemail'    => esc_html__( 'Left Voicemail', 'sdweddingdirectory' ),
                'texted'            => esc_html__( 'Texted', 'sdweddingdirectory' ),
                'called'            => esc_html__( 'Called', 'sdweddingdirectory' ),
                'meeting_scheduled' => esc_html__( 'Meeting Scheduled', 'sdweddingdirectory' ),
                'quote_sent'        => esc_html__( 'Quote Sent', 'sdweddingdirectory' ),
                'quote_viewed'      => esc_html__( 'Quote Viewed', 'sdweddingdirectory' ),
            ];
        }

        /**
         *  Get New (unread) Request Count
         *  ------------------------------
         */
        public static function get_new_request_count(){

            $query = new WP_Query( [
                'post_type'      => esc_attr( 'venue-request' ),
                'post_status'    => esc_attr( 'publish' ),
                'posts_per_page' => -1,
                'fields'         => 'ids',
                'meta_query'     => [
                    'relation' => 'AND',
                    [
                        'key'     => esc_attr( 'vendor_id' ),
                        'type'    => esc_attr( 'numeric' ),
                        'compare' => '=',
                        'value'   => absint( parent:: post_id() ),
                    ],
                ],
            ] );

            $new_count = 0;

            if( $query->have_posts() ){

                foreach( $query->posts as $pid ){

                    $status = get_post_meta( absint( $pid ), sanitize_key( 'sdwd_lead_status' ), true );

                    if( empty( $status ) || $status === 'new' ){
                        $new_count++;
                    }
                }
            }

            wp_reset_postdata();

            return absint( $new_count );
        }

        /**
         *  Lead Management Panel HTML
         *  --------------------------
         */
        public static function lead_management_panel( $request_post_id = 0 ){

            $request_post_id = absint( $request_post_id );

            if( $request_post_id === 0 ){
                return '';
            }

            $current_status = get_post_meta( $request_post_id, sanitize_key( 'sdwd_lead_status' ), true );

            if( empty( $current_status ) ){
                $current_status = 'new';
            }

            $nonce = wp_create_nonce( 'sdwd_lead_management_' . $request_post_id );

            $status_options = '';

            foreach( self:: lead_status_options() as $key => $label ){

                $selected = ( $key === $current_status ) ? ' selected' : '';
                $status_options .= sprintf( '<option value="%s"%s>%s</option>', esc_attr( $key ), $selected, esc_html( $label ) );
            }

            $activity_tags = '';

            foreach( self:: activity_tag_options() as $key => $label ){

                $activity_tags .= sprintf(
                    '<button type="button" class="btn btn-sm btn-outline-secondary sdwd-activity-tag" data-tag="%s" data-request-id="%d" data-nonce="%s">%s</button> ',
                    esc_attr( $key ),
                    absint( $request_post_id ),
                    esc_attr( $nonce ),
                    esc_html( $label )
                );
            }

            $statuses = self:: lead_status_options();
            $status_label = isset( $statuses[ $current_status ] ) ? $statuses[ $current_status ] : ucfirst( $current_status );

            ob_start();
            ?>
            <div class="sdwd-lead-panel" data-request-id="<?php echo absint( $request_post_id ); ?>" data-nonce="<?php echo esc_attr( $nonce ); ?>">

                <hr class="my-3">

                <h6 class="fw-bold mb-3"><i class="fa fa-briefcase"></i> <?php esc_html_e( 'Lead Management', 'sdweddingdirectory' ); ?></h6>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold"><?php esc_html_e( 'Lead Status', 'sdweddingdirectory' ); ?></label>
                        <select class="form-control form-control-sm sdwd-lead-status" data-request-id="<?php echo absint( $request_post_id ); ?>">
                            <?php echo $status_options; ?>
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <span class="sdwd-status-badge sdwd-status-<?php echo esc_attr( $current_status ); ?>"><?php echo esc_html( $status_label ); ?></span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold"><?php esc_html_e( 'Quick Actions', 'sdweddingdirectory' ); ?></label>
                    <div class="sdwd-activity-tags d-flex flex-wrap gap-1"><?php echo $activity_tags; ?></div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold"><?php esc_html_e( 'Add Note', 'sdweddingdirectory' ); ?></label>
                    <textarea class="form-control form-control-sm sdwd-note-input" rows="2" placeholder="<?php esc_attr_e( 'Add a note about this lead...', 'sdweddingdirectory' ); ?>" data-request-id="<?php echo absint( $request_post_id ); ?>"></textarea>
                    <button type="button" class="btn btn-sm btn-default mt-1 sdwd-add-note" data-request-id="<?php echo absint( $request_post_id ); ?>">
                        <i class="fa fa-plus"></i> <?php esc_html_e( 'Add Note', 'sdweddingdirectory' ); ?>
                    </button>
                </div>

                <div class="mb-2">
                    <a href="javascript:" class="sdwd-toggle-history small" data-request-id="<?php echo absint( $request_post_id ); ?>">
                        <i class="fa fa-history"></i> <?php esc_html_e( 'View History', 'sdweddingdirectory' ); ?>
                    </a>
                    <div class="sdwd-history-timeline mt-2" id="sdwd-history-<?php echo absint( $request_post_id ); ?>" style="display:none;"></div>
                </div>

            </div>
            <?php

            return ob_get_clean();
        }

        /**
         *  Add Lead History Entry
         *  ----------------------
         */
        public static function add_lead_history( $request_post_id = 0, $entry = [] ){

            $request_post_id = absint( $request_post_id );

            if( $request_post_id === 0 || ! parent:: _is_array( $entry ) ){
                return false;
            }

            $history = get_post_meta( $request_post_id, sanitize_key( 'sdwd_lead_history' ), true );

            if( ! is_array( $history ) ){
                $history = [];
            }

            $entry['timestamp'] = time();

            array_unshift( $history, $entry );

            return update_post_meta( $request_post_id, sanitize_key( 'sdwd_lead_history' ), $history );
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Request_Quote_Database:: get_instance();
}