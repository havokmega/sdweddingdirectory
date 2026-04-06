<?php
/**
 *  SDWeddingDirectory Couple Guest List
 *  ----------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Dashboard_Guest_List_Info' ) && class_exists( 'SDWeddingDirectory_Dashboard_Guest_List' ) ){

    /**
     *  SDWeddingDirectory Couple Guest List
     *  ----------------------------
     */
    class SDWeddingDirectory_Dashboard_Guest_List_Info extends SDWeddingDirectory_Dashboard_Guest_List{

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
             *  1. Dashboard Scripts
             *  --------------------
             */
            add_action( 'wp_enqueue_scripts', [$this, 'sdweddingdirectory_script'], absint( '50' ) );

            /**
             *  2. Dashboard Page
             *  -----------------
             */
            add_action( 'sdweddingdirectory/couple-dashboard', [$this, 'dashboard_page'], absint( '50' ), absint( '1' ) );
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
            if( parent:: dashboard_page_set( esc_attr( 'guest-management' ) ) ) {

                wp_enqueue_style( 'date-table',

                    SDWEDDINGDIRECTORY_GUEST_LIST_LIB . 'data-table/data-table.css',

                    [], 

                    SDWEDDINGDIRECTORY_GUEST_LIST_PLUGIN_VERSION, 

                    'all' 
                );

                wp_enqueue_style( 'jquery.dataTables',  

                    SDWEDDINGDIRECTORY_GUEST_LIST_LIB . 'data-table/jquery.dataTables.min.css', 

                    [],

                    SDWEDDINGDIRECTORY_GUEST_LIST_PLUGIN_VERSION, 

                    'all' 
                );

                wp_enqueue_style( 'buttons.dataTables',

                    SDWEDDINGDIRECTORY_GUEST_LIST_LIB . 'data-table/buttons.dataTables.min.css', 

                    [],

                    SDWEDDINGDIRECTORY_GUEST_LIST_PLUGIN_VERSION, 

                    'all' 
                );

                wp_enqueue_script( 'date-table', 

                    SDWEDDINGDIRECTORY_GUEST_LIST_LIB . 'data-table/data-table.min.js', 

                    array( 'jquery' ), 

                    SDWEDDINGDIRECTORY_GUEST_LIST_PLUGIN_VERSION, true 
                );

                wp_enqueue_script( 'jquery.dataTables',

                    SDWEDDINGDIRECTORY_GUEST_LIST_LIB . 'data-table/jquery.dataTables.min.js', 

                    array( 'jquery' ), 

                    SDWEDDINGDIRECTORY_GUEST_LIST_PLUGIN_VERSION, true 
                );

                wp_enqueue_script( 'dataTables.buttons', 

                    SDWEDDINGDIRECTORY_GUEST_LIST_LIB . 'data-table/dataTables.buttons.min.js', 

                    array( 'jquery' ), 

                    SDWEDDINGDIRECTORY_GUEST_LIST_PLUGIN_VERSION, true 
                );

                wp_enqueue_script( 'buttons.flash', 

                    SDWEDDINGDIRECTORY_GUEST_LIST_LIB . 'data-table/buttons.flash.min.js', 

                    array( 'jquery' ), 

                    SDWEDDINGDIRECTORY_GUEST_LIST_PLUGIN_VERSION, true 
                );

                wp_enqueue_script( 'jszip', 

                    SDWEDDINGDIRECTORY_GUEST_LIST_LIB . 'data-table/jszip.min.js', 

                    array( 'jquery' ), 

                    SDWEDDINGDIRECTORY_GUEST_LIST_PLUGIN_VERSION, true 
                );

                wp_enqueue_script( 'pdfmake', 

                    SDWEDDINGDIRECTORY_GUEST_LIST_LIB . 'data-table/pdfmake.min.js', 

                    array( 'jquery' ), 

                    SDWEDDINGDIRECTORY_GUEST_LIST_PLUGIN_VERSION, true 
                );

                wp_enqueue_script( 'vfs_fonts', 

                    SDWEDDINGDIRECTORY_GUEST_LIST_LIB . 'data-table/vfs_fonts.js', 

                    array( 'jquery' ), 

                    SDWEDDINGDIRECTORY_GUEST_LIST_PLUGIN_VERSION, true 
                );

                wp_enqueue_script( 'buttons.html5', 

                    SDWEDDINGDIRECTORY_GUEST_LIST_LIB . 'data-table/buttons.html5.min.js', 

                    array( 'jquery' ), 

                    SDWEDDINGDIRECTORY_GUEST_LIST_PLUGIN_VERSION, true 
                );

                wp_enqueue_script( 'buttons.print', 

                    SDWEDDINGDIRECTORY_GUEST_LIST_LIB . 'data-table/buttons.print.min.js', 

                    array( 'jquery' ), 

                    SDWEDDINGDIRECTORY_GUEST_LIST_PLUGIN_VERSION, true 
                );

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
                    [ 'jquery', 'date-table', 'slide-reveal', 'toastr',  'clipboard' ],

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

                /**
                 *  Side reveal Library request
                 *  ---------------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/slide-reveal', function( $args = [] ){

                    return  array_merge( $args, [  'couple-guest-list'   =>  true  ] );

                } );
            }           
        }

        /**
         *  2. Dashboard Page
         *  -----------------
         */
        public static function dashboard_page( $args = [] ){

            /**
             *  Have Args
             *  ---------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args,[

                    'layout'        =>      absint( '1' ),

                    'post_id'       =>      '',

                    'page'          =>      ''

                ] ) );

                /**
                 *  Make sure this page is couple dashboard page
                 *  --------------------------------------------
                 */
                if( ! empty( $page ) && $page == esc_attr( 'guest-management' )  ){

                    ?><div class="container-fluid"><?php

                        /**
                         *  2.1 Load Title
                         *  --------------
                         */
                        printf('<div class="section-title">
                                    <div class="row">

                                        <div class="col"><h2>%1$s</h2></div>

                                    </div>
                                </div>',

                                /**
                                 *  1. Title
                                 *  --------
                                 */
                                esc_attr__( 'Guest Management', 'sdweddingdirectory-guest-list' )
                        );

                        /**
                         *  2.2: Load Page of Data
                         *  ----------------------
                         */
                        self:: guest_list_page_content();

                    ?></div><?php
                }
            }
        }

        /**
         *  Load Guest Form with Button *TRRINGER* event
         *  --------------------------------------------
         */
        public static function guest_list_buttons( $args = [] ){

            $_default = array(

                'button_id'     =>  '',

                'button_text'   =>  esc_attr( 'Button Text' ),

                'print'         =>  true,

                'data_attr'     =>  '',
            );

            $args   =   wp_parse_args( $args, $_default );

            $_data_attr = '';

            if( parent:: _is_array( $args[ 'data_attr' ] ) ){

                foreach ( $args[ 'data_attr' ] as $key => $value) {

                    if( isset( $value  ) && $value !== '' ){

                        $_data_attr .=

                        sprintf( '%1$s="%2$s"', 

                            /**
                             *  1. Data Key
                             *  -----------
                             */
                            sanitize_key( $key ),
                            
                            /**
                             *  2. Data Value
                             *  -------------
                             */
                            esc_attr( $value )
                        );

                    }
                }
            }

            $_get_button   = 

            sprintf( '<button class="btn btn-outline-white me-3 btn-sm sdweddingdirectory_open_form %3$s" %1$s %4$s><i class="fa fa-plus"></i> %2$s</button> ', 

                /**
                 *  1. Button ID
                 *  ------------
                 */
                ( isset( $args[ 'button_id' ] ) && $args[ 'button_id' ] !== '' )

                ?   sprintf( 'id="%1$s"', esc_attr( $args[ 'button_id' ] ) )

                :   '',

                /**
                 *  2. Button Text
                 *  --------------
                 */
                ( isset( $args[ 'button_text' ] ) && $args[ 'button_text' ] !== '' )

                ?   esc_attr( $args[ 'button_text' ] )

                :   '',

                /**
                 *  3. Form Class
                 *  -------------
                 */
                ( isset( $args[ 'data_attr' ][ 'data-class' ] ) && $args[ 'data_attr' ][ 'data-class' ] !== '' )

                ?   sanitize_html_class( $args[ 'data_attr' ][ 'data-class' ] )

                :   '',

                /**
                 *  4. Data Attr
                 *  ------------
                 */
                $_data_attr

            );

            if( isset( $args[ 'print' ] ) && $args[ 'print' ] == true ){

                print $_get_button;

            }else{

                return $_get_button;
            }
        }

        public static function guest_list_page_content(){

            /**
             *  Extract Args
             *  ------------
             */
            extract( [

                'events'            =>      parent:: event_list(),

                'overview_id'       =>      esc_attr( parent:: _rand() ),

                'counter'           =>      absint( '1' ),

                'tab_counter'       =>      absint( '1' ),

            ] );

            /**
             *  Events
             *  ------
             */
            if( parent:: _is_array( $events ) ){

                /**
                 *  Have Events then insert first tab arguments
                 *  -------------------------------------------
                 */
                array_unshift(

                    $events,

                    array(

                        'event_list'            =>      esc_attr__( 'Overview', 'sdweddingdirectory-guest-list' ),

                        'event_unique_id'       =>      esc_attr( $overview_id ),
                    )
                );


                ?><ul class="nav nav-pills mb-3 horizontal-tab-second tabbing-scroll" id="pills-tab1" role="tablist"><?php

                foreach ( $events as $key => $value ){
                  
                    printf( '<li class="nav-item" role="presentation">

                                <a class="nav-link %1$s" id="sdweddingdirectory-%2$s-tab" data-bs-toggle="pill" href="#sdweddingdirectory-%2$s" role="tab" aria-controls="sdweddingdirectory-%2$s" aria-selected="true">%3$s</a>

                            </li>',

                        /**
                         *  1. Tab is Active ?
                         *  ------------------
                         */
                         ( $counter == absint( '1' ) ) 

                         ?  sanitize_html_class( 'active' )

                         :  '',

                         /**
                          *  2. Tab id
                          *  ---------
                          */
                         sanitize_title( $value[ 'event_unique_id' ] ),

                         /**
                          *  3. Tab Name
                          *  -----------
                          */
                         esc_attr( $value[ 'event_list' ] )
                    );

                    $counter++;
                }

                /** 
                 *  Add New Event Form Button
                 *  -------------------------
                 */
                printf( '<li class="nav-item ms-auto tab-add-new" role="presentation">

                            <a class="nav-link sdweddingdirectory_open_form mouse-pointer" data-form="%1$s" id="%2$s">%3$s</a>

                        </li>',

                        /**
                         *  1. Sidebar Form ID
                         *  ------------------
                         */
                        esc_attr( 'sdweddingdirectory_guest_event_sidepanel' ),

                        /**
                         *  2. Button ID
                         *  ------------
                         */
                        esc_attr( 'sdweddingdirectory_add_new_event' ),

                        /**
                         *  3. Button Text
                         *  --------------
                         */
                        /**
                         *  Add New Event
                         *  -------------
                         */
                        sprintf( '%1$s <i class="fa fa-plus"></i>',

                            /**
                             *  1. Tag
                             *  ------
                             */
                            esc_attr__( 'New Event', 'sdweddingdirectory-guest-list' )  
                        )
                );

                ?></ul><?php


                ?><div class="tab-content" id="pills-tabContent1"><?php

                foreach ( $events as $key => $value) {
                  
                    /**
                     *  Tab Content
                     *  -----------
                     */
                    printf('<div class="tab-pane fade %1$s" id="sdweddingdirectory-%2$s" role="tabpanel" aria-labelledby="sdweddingdirectory-%2$s-tab">',

                        /**
                         *  1. Tab is Active ?
                         *  ------------------
                         */
                        ( $tab_counter == absint( '1' ) )

                        ?   sprintf( '%1$s %2$s', sanitize_html_class( 'active' ), sanitize_html_class( 'show' ) )

                        :   '',

                        /**
                         *  2. Tab id
                         *  ---------
                         */
                        sanitize_title( $value[ 'event_unique_id' ] )
                    );

                    /**
                     *  Get Guest Data
                     *  --------------
                     */
                    
                    if( sanitize_title( $value[ 'event_unique_id' ] ) == sanitize_title( $overview_id ) ){

                        /**
                         *  1. Table Header
                         *  ---------------
                         */
                        self:: table_header();

                        /**
                         *  2. Load Overview
                         *  ----------------
                         */
                        self:: overview_create_table();

                    }else{

                        /**
                         *  1. Table Header
                         *  ---------------
                         */
                        self:: table_header( $value );

                        /**
                         *  2. Number of Event with Guest data
                         *  ----------------------------------
                         */
                        self:: create_table(

                            /**
                             *  1. This Current Event Data
                             *  --------------------------
                             */
                            $value
                        );
                    }

                    self:: table_footer();

                    ?></div><?php

                    $tab_counter++;
                }

                ?></div><?php
            }

            /**
             *  Default Guest List Data Import in Couple Post
             *  ---------------------------------------------
             */
            else{

                self:: default_data_setup();
            }
        }

        /**
         *  Default Data Setup
         *  ------------------
         */
        public static function default_data_setup(){

            /**
             *  Couple Post ID
             *  --------------
             */
            $couple_post_id         =       absint( parent:: post_id() );

            /**
             *  Import Event
             *  ------------
             */
            parent:: default_guest_list_event_data( [   'post_id'  =>   $couple_post_id     ] );

            /**
             *  Import Group
             *  ------------
             */
            parent:: default_guest_list_group_data( [   'post_id'  =>   $couple_post_id     ] );

            /**
             *  Import Menu
             *  -----------
             */
            parent:: default_guest_list_menu_data( [   'post_id'  =>   $couple_post_id     ] );

            /**
             *  Load this function again
             *  ------------------------
             */
            self:: guest_list_page_content();
        }

        /**
         *  1. Create Event Table Headings
         *  ------------------------------
         */
        public static function table_head_events_list(){

            /**
             *  Events
             *  ------
             */
            $_events_list   =   parent:: event_list();

            /**
             *  Counter
             *  -------
             */
            $_counter       =   absint( '0' );

            /**
             *  Have Data ?
             *  -----------
             */
            if( parent:: _is_array( $_events_list ) ){

                /**
                 *  Loop
                 *  ----
                 */
                foreach ( $_events_list as $key => $value) {

                    if( $_counter == absint( '3' ) ){

                        return;
                    }
                    
                    /**
                     *  Only 3 Event Will Show
                     *  ----------------------
                     */
                    printf( '<th scope="col" class="%2$s_table_head">%1$s</th>',

                            /**
                             *  1. Event Name
                             *  -------------
                             */
                            esc_attr( $value[ 'event_list' ] ),

                            /**
                             *  2. Event Heading Class
                             */
                            absint( $value[ 'event_unique_id' ] )
                    );

                    $_counter++;
                }
            }
        }

        /**
         *  Table TR with Guest ID Class
         *  ----------------------------
         */
        public static function table_tr( $data = [] ){

            if( parent:: _is_array( $data ) ){

                $_guest_id      =   parent:: _is_set( $data[ 'guest_unique_id' ] )

                                ?   absint( $data[ 'guest_unique_id' ] )

                                :   '';

                printf( '<tr class="%1$s">',

                    /**
                     *  1. Guest Unique ID
                     *  ------------------
                     */
                    sanitize_html_class(  $_guest_id )
                );
            }
        }

        /**
         *  First Name + Last Name
         *  ----------------------
         */
        public static function _get_guest_name( $data = [] ){

            /**
             *  Have Data ?
             *  -----------
             */
            if( parent:: _is_array( $data ) ){

                printf( '<td class="text-nowrap">%1$s %2$s</td>',

                    /**
                     *  1. First Name
                     *  -------------
                     */
                    parent:: _is_set( $data[ 'first_name' ] )

                    ?   esc_attr( $data[ 'first_name' ] )

                    :   '',

                    /**
                     *  2. Last Name
                     *  -------------
                     */
                    parent:: _is_set( $data[ 'last_name' ] )

                    ?   esc_attr( $data[ 'last_name' ] )

                    :   ''
                );
            }
        }

        /** 
         *  Guest Attended Events List
         *  ---------------------------
         */
        public static function _get_guest_attended_events( $guest_data = [], $event_data = [] ){

            if( parent:: _is_array( $guest_data ) ){

                if( isset( $guest_data[ 'guest_event' ] ) && $guest_data[ 'guest_event' ] !== '' ){

                    $_events_data = json_decode( $guest_data[ 'guest_event' ], true );

                    if( parent:: _is_array( $_events_data ) ){

                        if( parent:: _is_array( $event_data ) ){

                            foreach ( $_events_data as $_event_key => $_event_value ) {

                                if( $_event_value[ 'event_unique_id' ] == $event_data[ 'event_unique_id' ] ){

                                    ?><!-- Events list --><td scope="col"><?php

                                    parent:: get_event_attendance( array(

                                        /**
                                         *  1. Guest Attended ?
                                         *  --------------------
                                         */
                                        'guest_invited'      =>

                                        (  isset( $_event_value[ 'guest_invited' ] ) && $_event_value[ 'guest_invited' ] !== '' )

                                        ?   absint( $_event_value[ 'guest_invited' ] )

                                        :   '',

                                        /**
                                         *  2. Member ID
                                         *  ------------
                                         */
                                        'guest_unique_id'    => 

                                        ( isset( $guest_data[ 'guest_unique_id' ] ) && $guest_data[ 'guest_unique_id' ] !== '' )

                                        ?   absint( $guest_data[ 'guest_unique_id' ] )

                                        :   '',

                                        /**
                                         *  3. Event ID
                                         *  -----------
                                         */
                                        'event_unique_id'     =>   

                                        ( isset( $_event_value[ 'event_unique_id' ] ) && $_event_value[ 'event_unique_id' ] !== '' )

                                        ?   absint( $_event_value[ 'event_unique_id' ] )

                                        :   '',

                                    ) );

                                    ?></td><!-- / Events list --><?php

                                }
                            }

                        }else{

                            $_counter   =   absint( '0' );

                            foreach ( $_events_data as $_event_key => $_event_value ) {


                                if( $_counter == absint( '3' ) ){

                                    return;
                                }

                                $_counter++;

                                ?><!-- Events list --><td scope="col"><?php

                                parent:: get_event_attendance( array(

                                    /**
                                     *  1. Guest Attended ?
                                     *  --------------------
                                     */
                                    'guest_invited'      =>

                                    (  isset( $_event_value[ 'guest_invited' ] ) && $_event_value[ 'guest_invited' ] !== '' )

                                    ?   absint( $_event_value[ 'guest_invited' ] )

                                    :   '',

                                    /**
                                     *  2. Member ID
                                     *  ------------
                                     */
                                    'guest_unique_id'    => 

                                    ( isset( $guest_data[ 'guest_unique_id' ] ) && $guest_data[ 'guest_unique_id' ] !== '' )

                                    ?   absint( $guest_data[ 'guest_unique_id' ] )

                                    :   '',

                                    /**
                                     *  3. Event ID
                                     *  -----------
                                     */
                                    'event_unique_id'     =>   

                                    ( isset( $_event_value[ 'event_unique_id' ] ) && $_event_value[ 'event_unique_id' ] !== '' )

                                    ?   absint( $_event_value[ 'event_unique_id' ] )

                                    :   '',

                                ) );

                                ?></td><!-- / Events list --><?php
                            }

                        }
                    }
                }   
            }
        }

        /** 
         *  Guest Group List
         *  ----------------
         */
        public static function _get_guest_group( $guest_data = [] ){

            if( parent:: _is_array( $guest_data ) ){

                ?><td scope="col"><?php

                parent:: guest_group( array(

                    /**
                     *  1. Guest Attended ?
                     *  --------------------
                     */
                    'group_name'      =>

                    (  isset( $guest_data[ 'guest_group' ] ) && $guest_data[ 'guest_group' ] !== '' )

                    ?   sanitize_title( $guest_data[ 'guest_group' ] )

                    :   '',

                    /**
                     *  2. Member ID
                     *  ------------
                     */
                    'guest_unique_id'    => 

                    ( isset( $guest_data[ 'guest_unique_id' ] ) && $guest_data[ 'guest_unique_id' ] !== '' )

                    ?   absint( $guest_data[ 'guest_unique_id' ] )

                    :   ''

                ) );

                ?></td><?php
            }
        }

        /** 
         *  Guest Contact Details
         *  ---------------------
         */
        public static function guest_contact( $guest_data = [] ){

            /**
             *  Have Guest Data ?
             *  -----------------
             */
            if( parent:: _is_array( $guest_data ) ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( $guest_data );

                /**
                 *  Make sure any one is empty!
                 *  ---------------------------
                 */
                if( empty( $guest_contact ) && empty( $guest_address ) && empty( $guest_email ) ){

                    printf( '<td class="invitation_status text-center">

                                <a  class="btn-link-default request_missing_info_link fw-bolder" href="javascript:" role="button" 

                                    data-href="%3$s"

                                    data-bs-toggle="modal" data-bs-target="#%1$s" data-bs-dismiss="modal">%2$s</a>

                            </td>',

                            /**
                             *  1. Form ID
                             *  ----------
                             */
                            esc_attr( parent:: popup_id( 'request_missing_info' ) ),

                            /**
                             *  2. Translation Ready String
                             *  ---------------------------
                             */
                            esc_attr__( 'Request missing info', 'sdweddingdirectory-guest-list' ),

                            /**
                             *  3. Data Href
                             *  ------------
                             */
                            esc_url(    add_query_arg(

                                /**
                                 *  Args
                                 *  ----
                                 */
                                [
                                    'user'      =>      absint( parent:: post_id() ),

                                    'guest'     =>      esc_attr( $guest_unique_id )
                                ],

                                /**
                                 *  Page Template
                                 *  -------------
                                 */
                                apply_filters( 'sdweddingdirectory/template/link', esc_attr( 'request-missing-info.php' ) )

                            ) )
                    );
                }

                /**
                 *  Else
                 *  ----
                 */
                else{

                    /**
                     *   Collection
                     *   ----------
                     */
                    $_collection    =   '';

                    /**
                     *  Have Contact Number ?
                     *  ---------------------
                     */
                    $_collection    .=      sprintf( '<a    href="javascript:" 

                                                            data-guest-id="%1$s" id="edit_%1$s" 

                                                            data-class="edit_guest_member_form" 

                                                            data-form="sdweddingdirectory_guest_edit_sidepanel" 

                                                            data-width="800" 

                                                            class="action-links guest_edit sdweddingdirectory_open_form edit_guest_member_form edit mx-2">

                                                                <i class="fa fa-phone %2$s" aria-hidden="true"></i>

                                                    </a>',

                                                /**
                                                 *  1. Guest ID
                                                 *  -----------
                                                 */
                                                esc_attr( $guest_unique_id ),

                                                /**
                                                 *  2. Color
                                                 *  --------
                                                 */
                                                empty( $guest_contact )

                                                ?   sanitize_html_class( 'empty-field' )

                                                :   sanitize_html_class( 'text-muted' )
                                            );

                    /**
                     *  Have Address ?
                     *  --------------
                     */
                    $_collection    .=      sprintf( '<a    href="javascript:" 

                                                            data-guest-id="%1$s" id="edit_%1$s" 

                                                            data-class="edit_guest_member_form" 

                                                            data-form="sdweddingdirectory_guest_edit_sidepanel" 

                                                            data-width="800" 

                                                            class="action-links guest_edit sdweddingdirectory_open_form edit_guest_member_form edit mx-2">

                                                                <i class="fa fa-address-card-o %2$s" aria-hidden="true"></i>

                                                    </a>',

                                                /**
                                                 *  1. Guest ID
                                                 *  -----------
                                                 */
                                                esc_attr( $guest_unique_id ),

                                                /**
                                                 *  2. Color
                                                 *  --------
                                                 */
                                                empty( $guest_address )

                                                ?   sanitize_html_class( 'empty-field' )

                                                :   sanitize_html_class( 'text-muted' )
                                            );

                    /**
                     *  Make sure guest email not empty
                     *  -------------------------------
                     */
                    if( empty( $guest_email ) ){

                        $_collection    .=      sprintf( '<a    href="javascript:" 

                                                                data-guest-id="%1$s" id="edit_%1$s" 

                                                                data-class="edit_guest_member_form" 

                                                                data-form="sdweddingdirectory_guest_edit_sidepanel" 

                                                                data-width="800" 

                                                                class="action-links guest_edit sdweddingdirectory_open_form edit_guest_member_form edit mx-2">

                                                                    <i class="fa fa-envelope-o %2$s" aria-hidden="true"></i>

                                                        </a>',

                                                    /**
                                                     *  1. Guest ID
                                                     *  -----------
                                                     */
                                                    esc_attr( $guest_unique_id ),

                                                    /**
                                                     *  2. Color
                                                     *  --------
                                                     */
                                                    empty( $guest_email )

                                                    ?   sanitize_html_class( 'empty-field' )

                                                    :   sanitize_html_class( 'text-muted' )
                                                );
                    }

                    else{

                        /**
                         *  Sending RSVP via link
                         *  ---------------------
                         */
                        $_collection    .=      sprintf(    '<div class="dropdown">

                                                                <a  class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">

                                                                    <i class="fa fa-envelope-o %2$s" aria-hidden="true"></i>

                                                                </a>

                                                                <ul class="dropdown-menu py-0">

                                                                    <div class="card-body text-center">

                                                                        <a class="" href="%3$s">

                                                                            <i class="fa fa-check-square-o me-2" aria-hidden="true"></i> 

                                                                            <span>%4$s</span>

                                                                        </a>

                                                                    </div>

                                                                </ul>

                                                            </div>',

                                                            /**
                                                             *  1. Guest ID
                                                             *  -----------
                                                             */
                                                            esc_attr( $guest_unique_id ),

                                                            /**
                                                             *  2. Color
                                                             *  --------
                                                             */
                                                            empty( $guest_email )

                                                            ?   sanitize_html_class( 'empty-field' )

                                                            :   sanitize_html_class( 'text-muted' ),

                                                            /**
                                                             *  3. Link
                                                             *  -------
                                                             */
                                                            add_query_arg( 

                                                                /**
                                                                 *  Query Parameter
                                                                 *  ---------------
                                                                 */
                                                                [   'guest-id'   =>   esc_attr( $guest_unique_id )  ],

                                                                /**
                                                                 *  Page link
                                                                 *  ---------
                                                                 */
                                                                apply_filters( 'sdweddingdirectory/couple-menu/page-link', esc_attr( 'guest-management-rsvp' ) ) 
                                                            ),

                                                            /**
                                                             *  4. Translation Ready String
                                                             *  ---------------------------
                                                             */
                                                            esc_attr__( 'Request RSVP', 'sdweddingdirectory-guest-list' )
                                                );
                    }

                    ?><td class="invitation_status"><div class="d-flex justify-content-evenly"><?php

                        print   $_collection;

                    ?></div></td><?php
                }



                if( isset( $invitation_status ) && $invitation_status !== '' && false ){

                    ?>
                    <!-- Guest Contact Info --->
                    <td class="invitation_status text-center">
                    <?php  

                        printf('<div class="mb-3">
                                    <div class=" form-light">
                                        <input autocomplete="off" type="checkbox" class="form-check-input sent_invitation" data-guest-id="%1$s" id="%1$s_invitation_status" %2$s %3$s>
                                        <label class="form-check-label" for="%1$s_invitation_status"></label>
                                    </div>
                                </div>',

                                /**
                                 *  1. Checkbox ID
                                 *  --------------
                                 */
                                absint( $guest_unique_id ),

                                /**
                                 *  2. Checkbox Checked
                                 *  -------------------
                                 */
                                ( absint( $invitation_status ) !== absint( '0' ) )

                                ?   esc_attr( 'checked' )

                                :   '',

                                /**
                                 *  3. Checkbox Checked After Disable
                                 *  ---------------------------------
                                 */
                                ( absint( $invitation_status ) !== absint( '0' ) )

                                ?   esc_attr( 'disabled' )

                                :   ''
                        );
    
                    ?>
                    </td>
                    <!-- Guest Contact Info --->
                    <?php
                }
            }
        }

        /**
         *  Guest Edit + Removed Button
         *  ---------------------------
         */
        public static function guest_action_buttons( $_guest_data = [] ){

            if( parent:: _is_array( $_guest_data ) ){

                ?>
                <!-- Guest Contact Info --->
                <td class="text-nowrap text-center">
                <?php

                    printf( '<a href="javascript:" 

                                data-guest-id="%1$s" 

                                id="edit_%1$s" 

                                data-form="%2$s" 

                                data-width="%3$s" 

                                data-class="%4$s" 

                                class="action-links guest_edit sdweddingdirectory_open_form %4$s edit mx-2">

                                <i class="fa fa-pencil"></i>

                            </a>',

                            /**
                             *  1. Delete Guest ID
                             *  ------------------
                             */
                            esc_attr( $_guest_data[ 'guest_unique_id' ] ),

                            /**
                             *  2. Form ID
                             *  ----------
                             */
                            esc_attr( 'sdweddingdirectory_guest_edit_sidepanel' ),

                            /**
                             *  3. Form Width
                             *  -------------
                             */
                            absint( '800' ),

                            /**
                             *  4. Form Class + Class
                             *  ---------------------
                             */
                            sanitize_html_class( 'edit_guest_member_form' )
                    );

                    printf( '<a href="javascript:" data-guest-id="%1$s" id="delete_%1$s" data-guest-removed-alert="%2$s" class="action-links guest_removed"><i class="fa fa-trash"></i></a>', 

                            /**
                             *  1. Delete Guest ID
                             *  ------------------
                             */
                            absint( $_guest_data[ 'guest_unique_id' ] ),

                            /**
                             *  2. Confirm Alert
                             *  ----------------
                             */
                            esc_attr__( 'Are you sure to removed this guest ?', 'sdweddingdirectory-guest-list' )
                    );

                ?>
                </td>
                <!-- Guest Contact Info --->
                <?php
            }
        }

        /**
         *  Event Meal Dropdown
         *  -------------------
         */
        public static function guest_event_meal( $_guest_data = [], $_event_data = [] ){

            if( parent:: _is_array( $_guest_data ) && parent:: _is_array( $_event_data )  ){

                ?><!-- Get Menu List --><?php
                
                if( isset( $_event_data[ 'have_meal' ] ) && $_event_data[ 'have_meal' ] !== '' && $_event_data[ 'have_meal' ] == 'on' ){

                    if( isset( $_guest_data[ 'guest_event' ] ) && $_guest_data[ 'guest_event' ] !== '' ){

                        $_events_list = json_decode( $_guest_data[ 'guest_event' ], true );

                        if( parent:: _is_array( $_events_list ) ){

                            foreach ( $_events_list as $_event_key => $_event_value ) {

                                if( isset( $_event_data[ 'event_unique_id' ] ) && $_event_data[ 'event_unique_id' ] != '' ){

                                    if( $_event_value[ 'event_unique_id' ] == $_event_data[ 'event_unique_id' ] ){

                                        ?><td class="text-nowrap"><?php

                                            parent::get_event_meals( array(

                                                /**
                                                 *  1. Get Meal list
                                                 *  ----------------
                                                 */
                                                'event_meal'            =>   json_decode( $_event_data[ 'event_meal' ], true ), 

                                                /**
                                                 *  2. Guest Choose Meal
                                                 *  --------------------
                                                 */
                                                'guest_meal'            =>   esc_attr( $_event_value[ 'meal' ] ),

                                                /**
                                                 *  3. Member ID
                                                 *  ------------
                                                 */
                                                'guest_unique_id'       =>   absint( $_guest_data[ 'guest_unique_id' ] ),

                                                /**
                                                 *  4. Event ID
                                                 *  -----------
                                                 */
                                                'event_unique_id'      =>   absint( $_event_value[ 'event_unique_id' ] ),

                                            ) );

                                        ?></td><?php
                                    }
                                }
                            }
                        }
                    }
                }

                ?><!-- / Get Menu List --><?php
            }
        }

        /** 
         *  Group Result
         *  ------------
         */
        public static function group_result( $_data, $_key ){

            $_new_array = [];

            if( parent:: _is_array( $_data ) ){

                foreach ( $_data as $key => $value ) {

                    $_new_array[ $value[$_key] ][] =  $value;
                }
            }

            return $_new_array;
        }        

        /**
         *  Overview - Table
         *  ----------------
         */
        public static function overview_create_table(){

            $_guest_data = parent:: guest_list();

            /**
             *  Guest Table
             *  -----------
             */
            if( parent:: _is_array( $_guest_data ) ){

                ?>
                <div class="table-responsive">
                    <table class="table mb-0 sdweddingdirectory-datatable" id="<?php echo esc_attr( parent:: _rand() ); ?>">
                        <thead class="thead-light">
                                <tr>

                                    <th scope="col"><?php esc_attr_e( 'Guest Name', 'sdweddingdirectory-guest-list' ); ?></th>

                                    <th scope="col"><?php esc_attr_e( 'Group Name', 'sdweddingdirectory-guest-list' ); ?></th>
                                    
                                    <?php   self:: table_head_events_list();  ?>

                                    <th scope="col"><?php esc_attr_e( 'Contact', 'sdweddingdirectory-guest-list' ); ?></th>

                                    <th scope="col"><?php esc_attr_e( 'Action',  'sdweddingdirectory-guest-list' ); ?></th>
                                </tr>
                        </thead>
                        <tbody>
                        <?php

                            foreach ( $_guest_data as $guest_key => $guest_value ) {

                                /**
                                 *  Table <tr>
                                 *  ----------
                                 */
                                self:: table_tr( $guest_value );

                                        /**
                                         *  Guest Name
                                         *  ----------
                                         */
                                        self:: _get_guest_name( $guest_value );

                                        /**
                                         *  Group Name
                                         *  ----------
                                         */
                                        self:: _get_guest_group( $guest_value );

                                        /**
                                         *  Guest Event Attended List
                                         *  -------------------------
                                         */
                                        self:: _get_guest_attended_events( $guest_value );

                                        /**
                                         *  Guest Contact
                                         *  -------------
                                         */
                                        self:: guest_contact( $guest_value );

                                        /**
                                         *  Guest Edit + Removed Button
                                         *  ---------------------------
                                         */
                                        self:: guest_action_buttons( $guest_value );

                                /**
                                 *  Table </tr>
                                 *  -----------
                                 */
                                ?></tr><?php

                            } // foreach

                        ?>

                        </tbody>
                    </table>
                </div>

                <?php

            } // end if
        }

        /**
         *  Event Table Start
         *  -----------------
         */
        public static function create_table( $_event_data = [] ){

            $_guest_data    = parent:: guest_list();

            if( parent:: _is_array( $_guest_data ) && parent:: _is_array( $_event_data ) ){

                ?>
                    <div class="table-responsive">
                        <table class="table mb-0 sdweddingdirectory-datatable" id="<?php echo esc_attr( parent:: _rand() ); ?>">
                            <thead class="thead-light">
                                    <tr>
                                        <th scope="col"><?php esc_attr_e( 'Guest Name', 'sdweddingdirectory-guest-list' ); ?></th>
                                        <th scope="col"><?php esc_attr_e( 'Attendance', 'sdweddingdirectory-guest-list' ); ?></th>
                                        <?php

                                            /** 
                                             *   This Event Serve Meal Service ?
                                             *   -------------------------------
                                             */
                                            if( $_event_data[ 'have_meal' ] == 'on' ){

                                                ?><th scope="col"><?php esc_attr_e( 'Menu', 'sdweddingdirectory-guest-list' ); ?></th><?php
                                            }
                                        ?>
                                        <th scope="col"><?php esc_attr_e( 'Action',  'sdweddingdirectory-guest-list' ); ?></th>
                                    </tr>
                            </thead>
                            <tbody>
                            <?php

                                if( parent:: _is_array( $_guest_data ) ){

                                    foreach ( $_guest_data as $guest_key => $guest_value) {

                                        $_guest_event_list = json_decode( $guest_value[ 'guest_event' ], true );

                                        if( parent:: _is_array( $_guest_event_list ) ){

                                            foreach ( $_guest_event_list as $_event_key => $_event_value ) {
                                                
                                                if( $_event_value[ 'event_unique_id' ] == $_event_data[ 'event_unique_id' ]  ){

                                                    /**
                                                     *  Table <tr>
                                                     *  ----------
                                                     */
                                                    self:: table_tr( $guest_value );

                                                        /**
                                                         *  Guest Name
                                                         *  ----------
                                                         */
                                                        self:: _get_guest_name( $guest_value );

                                                        /**
                                                         *  Guest Event Attended List
                                                         *  -------------------------
                                                         */
                                                        self:: _get_guest_attended_events( $guest_value, $_event_value );

                                                        /**
                                                         *  Have Meal Option ?
                                                         *  ------------------
                                                         */
                                                        self:: guest_event_meal( $guest_value, $_event_data );

                                                        /**
                                                         *  Guest Edit + Removed Button
                                                         *  ---------------------------
                                                         */
                                                        self:: guest_action_buttons( $guest_value );

                                                    ?></tr><?php
                                                }
                                            }
                                        }
                                    }
                                }

                            ?>
                            </tbody>
                        </table>
                    </div>            
                <?php

            } // end if
        }

        public static function table_header( $event_list_value = [] ){

            if( parent:: _is_array( $event_list_value ) ){

                ?>
                <div class="card-shadow">
                    <div class="card-shadow-body">
                        <div class="couple-info p-0">
                            <div class="row row-cols-1 row-cols-xl-5 row-cols-lg-3 row-cols-md-3 row-cols-sm-2">
                                <div class="col">
                                    <?php

                                        /**
                                         *  Display Event Name with Icon
                                         *  ----------------------------
                                         */
                                        printf( '<div class="couple-status-item"> 

                                                    <div class="counter %3$s_event_icon">%2$s</div>

                                                    <div class="text">
                                                        <strong class="%3$s_event_name">%1$s</strong>
                                                    </div>

                                                </div>',

                                            /**
                                             *  1. Event Name
                                             *  -------------
                                             */
                                            ( isset( $event_list_value[ 'event_list' ] ) && $event_list_value[ 'event_list' ] !== '' )

                                            ?   esc_attr( $event_list_value[ 'event_list' ] )
                                            
                                            :   '',

                                            /**
                                             *  2. Event Icon
                                             *  -------------
                                             */
                                            ( isset( $event_list_value[ 'event_icon' ] ) && $event_list_value[ 'event_icon' ] !== '' )

                                            ?   sprintf( '<i class="%1$s"></i>', esc_attr( $event_list_value[ 'event_icon' ] ) )
                                            
                                            :   '',

                                            /**
                                             *  3. Event Unique id
                                             *  ------------------
                                             */
                                            absint( $event_list_value[ 'event_unique_id' ] )

                                        );

                                    ?>
                                </div>
                                <div class="col">
                                    <div class="couple-status-item">
                                        <?php

                                            /**
                                             *  1. Total Guest Counter
                                             *  ----------------------
                                             */
                                            printf( '<div class="counter %1$s_total_guest_counter">%2$s</div>', 

                                                /**
                                                 *  1. Event Unique id
                                                 *  ------------------
                                                 */
                                                absint( $event_list_value[ 'event_unique_id' ] ),

                                                /**
                                                 *  2. Get Total Number Of guest
                                                 *  ----------------------------
                                                 */
                                                parent:: _is_array( parent:: guest_list() )

                                                ?   absint( count(  parent:: guest_list()  ) )

                                                :   absint( '0' )
                                                
                                            );
                                        ?>
                                        <div class="text">
                                            <strong><?php esc_attr_e( 'Guests', 'sdweddingdirectory-guest-list' ); ?></strong>
                                            <small><?php esc_attr_e( 'From Total', 'sdweddingdirectory-guest-list' ); ?></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="couple-status-item">
                                        <?php

                                            /**
                                             *  1. Attended Guest Counter
                                             *  -------------------------
                                             */
                                            printf( '<div class="counter %1$s_attending_counter">%2$s</div>', 

                                                /**
                                                 *  1. declain unique id
                                                 *  --------------------
                                                 */
                                                absint( $event_list_value[ 'event_unique_id' ] ),

                                                /**
                                                 *  2. Get this event declain guest
                                                 *  -------------------------------
                                                 */
                                                parent:: get_event_counter( array(

                                                    /**
                                                     *  1. Event Unique ID
                                                     *  ------------------
                                                     */
                                                    'event_unique_id'   =>  absint( $event_list_value[ 'event_unique_id' ] ),

                                                    /**
                                                     *  2. Get * Attended * Counter
                                                     *  --------------------------
                                                     */
                                                    '__FIND__'          =>   array(

                                                        'guest_invited'     =>  absint( '2' )
                                                    )

                                                ) )

                                            );
                                        ?>
                                        <div class="text">
                                            <strong><?php esc_attr_e( 'Accepted', 'sdweddingdirectory-guest-list' ); ?></strong>
                                            <small><?php esc_attr_e( 'From Total', 'sdweddingdirectory-guest-list' ); ?></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="couple-status-item">
                                        <?php

                                            /**
                                             *  1. Invited Guest Counter
                                             *  ------------------------
                                             */
                                            printf( '<div class="counter %1$s_invited_counter">%2$s</div>', 

                                                /**
                                                 *  1. Event Unique id
                                                 *  --------------------
                                                 */
                                                absint( $event_list_value[ 'event_unique_id' ] ),

                                                /**
                                                 *  2. Get this event Invited guest
                                                 *  -------------------------------
                                                 */
                                                parent:: get_event_counter( array(

                                                    /**
                                                     *  1. Event Unique ID
                                                     *  ------------------
                                                     */
                                                    'event_unique_id'   =>  absint( $event_list_value[ 'event_unique_id' ] ),

                                                    /**
                                                     *  2. Get * Invided ( Pending ) * Counter
                                                     *  --------------------------------------
                                                     */
                                                    '__FIND__'          =>   array(

                                                        'guest_invited'     =>  absint( '1' )
                                                    )

                                                ) )

                                            );
                                        ?>
                                        <div class="text">
                                            <strong><?php esc_attr_e( 'Waiting', 'sdweddingdirectory-guest-list' ); ?></strong>
                                            <small><?php esc_attr_e( 'From Total', 'sdweddingdirectory-guest-list' ); ?></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="couple-status-item">
                                        <?php

                                            /**
                                             *  1. Declain Guest Counter
                                             *  ------------------------
                                             */
                                            printf( '<div class="counter %1$s_declain_counter">%2$s</div>', 

                                                /**
                                                 *  1. declain unique id
                                                 *  --------------------
                                                 */
                                                absint( $event_list_value[ 'event_unique_id' ] ),

                                                /**
                                                 *  2. Get this event declain guest
                                                 *  -------------------------------
                                                 */
                                                parent:: get_event_counter( array(

                                                    /**
                                                     *  1. Event Unique ID
                                                     *  ------------------
                                                     */
                                                    'event_unique_id'   =>  absint( $event_list_value[ 'event_unique_id' ] ),

                                                    /**
                                                     *  2. Get * Declain * Counter
                                                     *  --------------------------
                                                     */
                                                    '__FIND__'          =>   array(

                                                        'guest_invited'     =>  absint( '3' )
                                                    )

                                                ) )

                                            );
                                        ?>
                                        <div class="text">
                                            <strong><?php esc_attr_e( 'Declined', 'sdweddingdirectory-guest-list' ); ?></strong>
                                            <small><?php esc_attr_e( 'From Total', 'sdweddingdirectory-guest-list' ); ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php

            }else{

                ?><div class="card-shadow">
                    <div class="card-shadow-body"><div class="guest-events-cards"><?php

                    $_event_data =  parent:: get_event_overview_data();

                    if( parent:: _is_array( $_event_data ) ) {

                        foreach ( $_event_data as $key => $value) {

                            printf('<div class="col-auto pe-2">
                                        <div class="card-body">

                                            <div class="overview_event_icon counter %2$s_event_icon">%6$s</div>

                                            <div class="event-text">

                                                <div class="card-title %2$s_event_name">%1$s</div>

                                                <span><strong class="%2$s_attending_counter">%3$s</strong> %7$s</span>

                                                <span><strong class="%2$s_invited_counter">%4$s</strong> %8$s</span>

                                                <span><strong class="%2$s_declain_counter">%5$s</strong> %9$s</span>

                                            </div>
                                        </div>
                                    </div>',

                                    /**
                                     *  1. Event Title
                                     *  --------------
                                     */
                                    esc_attr( $value[ 'event_name' ] ),

                                    /**
                                     *  2. Event Unique ID
                                     *  ------------------
                                     */
                                    absint( $value[ 'event_unique_id' ] ),

                                    /**
                                     *  3. Attending Counter
                                     *  --------------------
                                     */
                                    absint( $value[ 'attendance' ] ),

                                    /**
                                     *  4. Get this event Invited guest
                                     *  -------------------------------
                                     */
                                    absint( $value[ 'invited' ] ),

                                    /**
                                     *  5. Get this event declain guest
                                     *  -------------------------------
                                     */
                                    absint( $value[ 'declain' ] ),

                                    /**
                                     *  6. Event Icon
                                     *  -------------
                                     */
                                    ( $value[ 'event_icon' ] !== '' )

                                    ?   sprintf( '<i class="%1$s"></i>', esc_attr( $value[ 'event_icon' ] )  )

                                    :   '',

                                    /**
                                     *  7. Translation Ready String
                                     *  ---------------------------
                                     */
                                    esc_attr__( 'Attending', 'sdweddingdirectory-guest-list' ),

                                    /**
                                     *  8. Translation Ready String
                                     *  ---------------------------
                                     */
                                    esc_attr__( 'Invited', 'sdweddingdirectory-guest-list' ),

                                    /**
                                     *  9. Translation Ready String
                                     *  ---------------------------
                                     */
                                    esc_attr__( 'Declined', 'sdweddingdirectory-guest-list' )
                            );
                        }
                    }

                ?></div></div></div><?php
            }

            ?>
            <div class="card-shadow">
                <div class="card-shadow-body">
                    <div class="d-flex align-items-center row">

                        <div class="col-md-8">
                            <div class="d-md-flex align-items-center">

                                <!-- <div class=" form-dark me-3">
                                    <input autocomplete="off" type="checkbox" class="form-check-input" id="checkAll">
                                    <label class="form-check-label" for="checkAll"><small>Select all</small></label>
                                </div> -->

                                <div class="mt-4 mt-md-0 guest-list-form-button">
                                    <?php

                                        $_guest_data = parent:: guest_list();

                                        $_event_data = parent:: event_list();

                                        if( parent:: _is_array( $_guest_data ) ){

                                            /**
                                             *  1. Export CSV
                                             *  -------------
                                             */
                                            printf( '<a href="javascript:" class="btn btn-outline-white me-3 btn-sm export_csv" %2$s>%1$s</a>', 

                                                /**
                                                 *  1. Translation Ready String.
                                                 *  ----------------------------
                                                 */
                                                esc_attr__( 'CSV', 'sdweddingdirectory-guest-list' ),

                                                /**
                                                 *  2. Get Event ID
                                                 *  ---------------
                                                 */
                                                ( isset( $event_list_value[ 'event_unique_id' ] ) &&  $event_list_value[ 'event_unique_id' ] !== '' )

                                                ?   sprintf( ' data-event-unique-id="%1$s" data-event-name="%2$s-event-%3$s" ',

                                                        /**
                                                         *  1. Event Unique ID
                                                         *  ------------------
                                                         */
                                                        absint( $event_list_value[ 'event_unique_id' ] ),

                                                        /**
                                                         *  1. Get Event Name With ID
                                                         *  -------------------------
                                                         */
                                                        sanitize_title( $event_list_value[ 'event_list' ] ),

                                                        /**
                                                         *  2. Get Today Time
                                                         *  -----------------
                                                         */
                                                        date( 'Y-m-d H:i:s' )
                                                    )

                                                :   ''
                                            );
                                        }

                                        self:: guest_list_buttons( array(

                                                'button_id'     =>  esc_attr( 

                                                                        sprintf( 'add_new_guest_button_id_%1$s', 

                                                                            esc_attr( parent:: _rand() )
                                                                        )
                                                                    ),

                                                'button_text'   =>  esc_attr__( 'Guest', 'sdweddingdirectory-guest-list' ),

                                                'data_attr'     =>  array(

                                                                        'data-form'             =>  esc_attr( 'sdweddingdirectory_guest_add_sidepanel' ),

                                                                        'data-width'            =>  absint( '600' ),

                                                                        'data-class'            =>  sanitize_html_class( 'add_new_guest_event' ),
                                                                    ),
                                        ) );

                                        /**
                                         *  2. Add Group Button
                                         *  -------------------
                                         */
                                        self:: guest_list_buttons( array(

                                                'button_id'     =>  esc_attr( 

                                                                        sprintf( 'add_new_group_event_button_id_%1$s', 

                                                                            esc_attr( parent:: _rand() )
                                                                        ) 
                                                                    ),

                                                'button_text'   =>  esc_attr__( 'Group', 'sdweddingdirectory-guest-list' ),

                                                'data_attr'     =>  array(

                                                                        'data-form'             =>  

                                                                            esc_attr( 'sdweddingdirectory_guest_group_managment_sidepanel' ),

                                                                        'data-width'            =>  '',

                                                                        'data-class'            =>  sanitize_html_class( 'guest_group_managment' ),
                                                                    ),
                                        ) );

                                        /**
                                         *  3. Import Guest Button
                                         *  ----------------------
                                         */
                                        // self:: guest_list_buttons( array(

                                        //         'button_id'     =>  esc_attr( 

                                        //                                 sprintf( 'import_guest_list_button_id_%1$s', 

                                        //                                     esc_attr( parent:: _rand() )
                                        //                                 ) 
                                        //                             ),

                                        //         'button_text'   =>  esc_attr__( 'Import Guest List', 'sdweddingdirectory-guest-list' ),

                                        //         'data_attr'     =>  array(

                                        //                                 'data-form'             =>  

                                        //                                     esc_attr( 'sdweddingdirectory_guest_import_sidepanel' ),

                                        //                                 'data-width'            =>  '',

                                        //                                 'data-class'            =>  sanitize_html_class( 'import_guest_list' ),
                                        //                             ),
                                        // ) );

                                        if( parent:: _is_array( $event_list_value ) && $event_list_value[ 'event_unique_id' ] !== '' ){

                                            $_event_unique_id  =    

                                            ( isset( $event_list_value[ 'event_unique_id' ] ) && $event_list_value[ 'event_unique_id' ] !== '' )

                                            ?   absint( $event_list_value[ 'event_unique_id' ] )

                                            :   absint( '0' );

                                            /**
                                             *  3. Add Menu Button
                                             *  ------------------
                                             */
                                            // self:: guest_list_buttons( array(

                                            //         'button_id'     =>  esc_attr( 

                                            //                                 sprintf( 'add_new_menu_event_button_id_%1$s', 

                                            //                                     esc_attr( parent:: _rand() )
                                            //                                 ) 
                                            //                             ),

                                            //         'data_attr'     =>  array(

                                            //                                 'data-event-unique-id'  =>

                                            //                                 (   isset( $_event_unique_id ) && $_event_unique_id !== ''  )
                                                                            
                                            //                                 ?   absint( $_event_unique_id )

                                            //                                 :   '',

                                            //                                 'data-form'             =>  esc_attr( 'sdweddingdirectory_guest_menu_sidepanel' ),

                                            //                                 'data-width'            =>  '',

                                            //                                 'data-class'            =>  sanitize_html_class( 'add_new_menu_event' ),
                                            //                             ),

                                            //         'button_text'   =>  esc_attr__( 'Menu', 'sdweddingdirectory-guest-list' ),
                                            // ) );

                                            /**
                                             *  4. Print Button With Trigger Event to load Sidebar Form
                                             *  -------------------------------------------------------
                                             */
                                            self:: guest_list_buttons( array(

                                                    'data_attr'     =>  array(

                                                                            'data-event-unique-id'  =>

                                                                            (   isset( $_event_unique_id ) && $_event_unique_id !== ''  )
                                                                            
                                                                            ?   absint( $_event_unique_id )

                                                                            :   '',

                                                                            'data-form'             =>  esc_attr( 'sdweddingdirectory_guest_edit_event_sidepanel' ),

                                                                            'data-width'            =>  absint( '400' ),

                                                                            'data-class'            =>  sanitize_html_class( 'open_edit_event' ),
                                                                        ),

                                                    'button_id'     =>  esc_attr( 

                                                                            sprintf( 'edit_event_button_%1$s', 

                                                                                esc_attr( parent:: _rand() )
                                                                            ) 
                                                                        ),

                                                    'button_text'   =>  esc_attr__( 'Edit Event', 'sdweddingdirectory-guest-list' ),
                                            ) );

                                            /**
                                             *  5. View Summary
                                             *  ---------------
                                             */
                                            if( parent:: _is_array( $_guest_data ) && parent:: _is_array( $_event_data ) ){

                                                /**
                                                 *  View Summary
                                                 *  ------------
                                                 */
                                                printf( '<a href="%1$s" class="btn btn-outline-white me-3 btn-sm"><i class="fa fa-plus"></i> %2$s</a>', 

                                                    /**
                                                     *  1. Summary Link
                                                     *  ---------------
                                                     */
                                                    add_query_arg( 

                                                        /**
                                                         *  Query Parameter
                                                         *  ---------------
                                                         */
                                                        [   'event-id'   =>   esc_attr( $_event_unique_id )  ],

                                                        /**
                                                         *  Page link
                                                         *  ---------
                                                         */
                                                        apply_filters( 'sdweddingdirectory/couple-menu/page-link', esc_attr( 'guest-management-statistic' ) ) 
                                                    ),

                                                    /**
                                                     *  2. Translation Ready String
                                                     *  ---------------------------
                                                     */
                                                    esc_attr__( 'View Summary', 'sdweddingdirectory-guest-list' )
                                                );
                                            }

                                        }
                                    ?>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-4 col-xl-2 ms-auto">

                            <div class="search-guest-list">

                                <i class="fa fa-search"></i>

                                <?php

                                    printf( '<input type="text" class="form-control form-dark rounded search-guest" id="%1$s" placeholder="%2$s" />',

                                        /**
                                         *  1. ID
                                         *  -----
                                         */
                                        esc_attr( parent:: _rand() ),

                                        /**
                                         *  2. Translation Ready String
                                         *  ---------------------------
                                         */
                                        esc_attr__( 'Search list', 'sdweddingdirectory-guest-list' )
                                    );

                                ?>

                            </div>

                        </div>

                    </div>
                </div>
            
                <?php /** Footer Ending Another Function **/

        }

        public static function table_footer(){

            ?></div><?php
        }

        /**
         *  2.2: Load Page of Data
         *  ----------------------
         */
        public static function in_future_used(){

            ?>
            <div class="row">

                <div class="col-4">
                  
                      <div class="card text-center">
                        <div class="card-header"><?php esc_attr_e( 'Menu Item', 'sdweddingdirectory-guest-list' ); ?></div>
                        <div class="card-body">
                          <ul class="list-group" id="sdweddingdirectory_list_of_menu_items">
                            <?php

                                $_menu_list = parent:: menu_list();

                                if( parent:: _is_array( $_menu_list ) ){

                                    foreach ( $_menu_list as $key => $value) {

                                        printf( '<li class="list-group-item d-flex justify-content-between align-items-center">
                                                      <span>%1$s</span>
                                                      <a href="javascript:" data-remove="%2$s" class="action-links"><i class="fa fa-trash"></i></a>
                                                  </li>',

                                                  /**
                                                   *  1. Menu Name
                                                   *  ------------
                                                   */
                                                  esc_attr( $value[ 'menu_list' ] ),

                                                  /**
                                                   *  1. Menu Unique ID
                                                   *  -----------------
                                                   */
                                                  absint( $value[ 'menu_unique_id' ] )
                                        );
                                    }
                                }
                            ?>
                            </ul>
                        </div>
                        <div class="card-footer text-muted">
                        <?php

                            printf('<div class="input-group mb-3">
                                      
                                        <input autocomplete="off" type="text" class="form-control" id="%1$s" placeholder="%2$s" required>

                                        <div class="input-group-append">
                                              <button id="%3$s" class="btn btn-outline-secondary" type="button"><i class="fa fa-plus"></i></button>
                                        </div>

                                    </div>',

                                    // 1
                                    esc_attr( 'guest_list_add_menu_item' ),

                                    // 2
                                    esc_attr__( 'Add Menu Item', 'sdweddingdirectory-guest-list' ),

                                    // 3
                                    esc_attr( 'guest_list_add_menu_item_button' )
                            );

                        ?>
                        </div>
                      </div>

                </div>

                <div class="col-4">
                  
                      <div class="card text-center">
                        <div class="card-header"><?php esc_attr_e( 'Add New Group', 'sdweddingdirectory-guest-list' ); ?></div>
                        <div class="card-body">
                          <ul class="list-group" id="group_list_section">
                            <?php

                                $_menu_list = parent:: group_list();

                                if( parent:: _is_array( $_menu_list ) ){

                                    foreach ( $_menu_list as $key => $value) {

                                        printf( '<li class="list-group-item d-flex justify-content-between align-items-center">
                                                      <span>%1$s</span>
                                                      <a href="javascript:" data-remove="%2$s" class="action-links"><i class="fa fa-trash"></i></a>
                                                  </li>',

                                                  /**
                                                   *  1. Menu Name
                                                   *  ------------
                                                   */
                                                  esc_attr( $value[ 'group_name' ] ),

                                                  /**
                                                   *  1. Menu Unique ID
                                                   *  -----------------
                                                   */
                                                  absint( $value[ 'group_unique_id' ] )
                                        );
                                    }
                                }
                            ?>
                            </ul>
                        </div>
                        <div class="card-footer text-muted">
                        <?php

                            printf('<div class="input-group mb-3">
                                      
                                        <input autocomplete="off" type="text" class="form-control" id="%1$s" placeholder="%2$s" required>

                                        <div class="input-group-append">
                                              <button id="%3$s" class="btn btn-outline-secondary" type="button"><i class="fa fa-plus"></i></button>
                                        </div>

                                    </div>',

                                    // 1
                                    esc_attr( 'group_item_name' ),

                                    // 2
                                    esc_attr__( 'Add New Group', 'sdweddingdirectory-guest-list' ),

                                    // 3
                                    esc_attr( 'group_item_add_button' )
                            );

                        ?>
                        </div>
                      </div>

                </div>

                <div class="col-4">
                  
                      <div class="card text-center">
                        <div class="card-header"><?php esc_attr_e( 'Add New Event', 'sdweddingdirectory-guest-list' ); ?></div>
                        <div class="card-body">
                          <ul class="list-group" id="event_list_section">
                            <?php

                                $data = parent:: event_list();

                                if( parent:: _is_array( $data ) ){

                                    foreach ( $data as $key => $value) {

                                        printf( '<li class="list-group-item d-flex justify-content-between align-items-center">
                                                      <span>%1$s</span>
                                                      <a href="javascript:" data-remove="%2$s" class="action-links"><i class="fa fa-trash"></i></a>
                                                  </li>',

                                                  /**
                                                   *  1. Menu Name
                                                   *  ------------
                                                   */
                                                  esc_attr( $value[ 'event_list' ] ),

                                                  /**
                                                   *  1. Menu Unique ID
                                                   *  -----------------
                                                   */
                                                  absint( $value[ 'event_unique_id' ] )
                                        );
                                    }
                                }
                            ?>
                            </ul>
                        </div>
                        <div class="card-footer text-muted">
                        <?php

                            printf('<div class="input-group mb-3">
                                      
                                        <input autocomplete="off" type="text" class="form-control" id="%1$s" placeholder="%2$s" required>

                                        <div class="input-group-append">
                                              <button id="%3$s" class="btn btn-outline-secondary" type="button"><i class="fa fa-plus"></i></button>
                                        </div>

                                    </div>',

                                    // 1
                                    esc_attr( 'event_list_name' ),

                                    // 2
                                    esc_attr__( 'Add New Event', 'sdweddingdirectory-guest-list' ),

                                    // 3
                                    esc_attr( 'event_list_add_button' )
                            );

                        ?>
                        </div>
                      </div>

                </div>

            </div>
            <?php
        }
    }

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Dashboard_Guest_List_Info:: get_instance();
}