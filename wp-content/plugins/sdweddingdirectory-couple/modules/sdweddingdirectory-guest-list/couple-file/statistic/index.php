<?php
/**
 *  SDWeddingDirectory Couple Guest List Statistic
 *  --------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Dashboard_Guest_List_Statistic' ) && class_exists( 'SDWeddingDirectory_Dashboard_Guest_List' ) ){

    /**
     *  SDWeddingDirectory Couple Guest List Statistic
     *  --------------------------------------
     */
    class SDWeddingDirectory_Dashboard_Guest_List_Statistic extends SDWeddingDirectory_Dashboard_Guest_List{

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
            add_action( 'wp_enqueue_scripts', [$this, 'sdweddingdirectory_script'], absint( '53' ) );

            /**
             *  2. Dashboard Page
             *  -----------------
             */
            add_action( 'sdweddingdirectory/couple-dashboard', [$this, 'dashboard_page'], absint( '53' ), absint( '1' ) );
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
            if( parent:: dashboard_page_set( esc_attr( 'guest-management-statistic' ) ) ) {

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

                /**
                 *  Pie Chart Library request
                 *  -------------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/pie-chart', function( $args = [] ){

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
                if( ! empty( $page ) && $page == esc_attr( 'guest-management-statistic' )  ){

                    /**
                     *  Make sure event id 
                     *  ------------------
                     */
                    if( isset( $_GET['event-id'] ) &&  ! empty( $_GET['event-id'] ) ){

                        /**
                         *  var
                         *  ---
                         */
                        $event_data         =       [];

                        $event_list         =       parent:: event_list();

                        /**
                         *  Event List
                         *  ----------
                         */
                        if( parent:: _is_array( $event_list ) ){

                            /**
                             *  Loop
                             *  ----
                             */
                            foreach( $event_list as $key => $value ){

                                /**
                                 *  Extract
                                 *  -------
                                 */
                                extract( $value );

                                /**
                                 *  Is Correct ?
                                 *  ------------
                                 */
                                if( $event_unique_id == $_GET['event-id'] ){

                                    $event_data       =       $value;
                                }
                            }
                        }

                        /**
                         *  Have Event Data ?
                         *  -----------------
                         */
                        if( parent:: _is_array( $event_data ) ){

                            ?><div class="container"><?php

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
                                        esc_attr__( 'Statistic', 'sdweddingdirectory-guest-list' )
                                );

                                /**
                                 *  2.2: Load Page of Data
                                 *  ----------------------
                                 */
                                self:: guest_list_page_content( $event_data );

                            ?></div><?php
                        }

                        else{

                            esc_attr_e( 'Event Not Found!', 'sdweddingdirectory-guest-list' );

                            exit();
                        }
                    }

                    else{

                        esc_attr_e( 'Event Not Found!', 'sdweddingdirectory-guest-list' );

                        exit();
                    }
                }
            }
        }

        /**
         *  Guest list page content
         *  -----------------------
         */
        public static function guest_list_page_content( $event_data = [] ){

            /**
             *  Events
             *  ------
             */
            $_events        =   parent:: event_list();

            /**
             *  Have Event
             *  ----------
             */
            if( parent:: _is_array( $_events ) ){

                /**
                 *  List
                 *  ----
                 */
                ?><ul class="nav nav-pills mb-3 horizontal-tab-second tabbing-scroll" id="pills-tab1" role="tablist"><?php

                /**
                 *  Have Events then insert first tab arguments
                 *  -------------------------------------------
                 */
                array_unshift(

                    /**
                     *  Events
                     *  ------
                     */
                    $_events,

                    /**
                     *  Merge Args
                     *  ----------
                     */
                    array(

                        'event_list'        =>  esc_attr__( 'Overview', 'sdweddingdirectory-guest-list' ),

                        'event_unique_id'   =>  esc_attr( 'overview' ),
                    )
                );

                /**
                 *  Have Events ?
                 *  -------------
                 */
                foreach( $_events as $key => $value ){

                    /**
                     *  Extract Args
                     *  ------------
                     */
                    extract( $value );
                  
                    /**
                     *  Load Tabs
                     *  ---------
                     */
                    printf( '<li class="nav-item" role="presentation">

                                <a  href="%1$s"  class="nav-link %2$s" id="sdweddingdirectory-%3$s-tab">%4$s</a>

                            </li>',

                            /**
                             *  1. Link
                             *  -------
                             */
                            esc_attr( 'overview' )  ==  esc_attr( $value[ 'event_unique_id' ] )

                            ?   apply_filters( 'sdweddingdirectory/couple-menu/page-link', esc_attr( 'guest-management' ) )

                            :   add_query_arg( 

                                    /**
                                     *  Query Parameter
                                     *  ---------------
                                     */
                                    [   'event-id'   =>   esc_attr( $value[ 'event_unique_id' ] )  ],

                                    /**
                                     *  Page link
                                     *  ---------
                                     */
                                    apply_filters( 'sdweddingdirectory/couple-menu/page-link', esc_attr( 'guest-management-statistic' ) ) 
                                ),

                            /**
                             *  2. Is Active Tab ?
                             *  ------------------
                             */
                            $_GET['event-id']   ==  $value[ 'event_unique_id' ]

                            ?   sanitize_html_class( 'active' )

                            :   '',

                            /**
                             *  3. ID
                             *  -----
                             */
                            sanitize_title( $value[ 'event_unique_id' ] ),

                            /**
                             *  4. Event Name
                             *  -------------
                             */
                            esc_attr( $value[ 'event_list' ] )
                    );
                }

                print '</ul>';

                /**
                 *  Tab Content
                 *  -----------
                 */
                printf( '<div class="tab-pane fade active show" id="sdweddingdirectory-%1$s" role="tabpanel" aria-labelledby="sdweddingdirectory-%1$s-tab">

                            <div class="card-shadow">

                                <h2 class="border-bottom py-4 px-4 mb-0">%2$s</h2>
                                
                                <div class="card-shadow-body pb-0">',

                        /**
                         *  1. Event ID
                         *  -----------
                         */
                        esc_attr( $_GET['event-id'] ),

                        /**
                         *  2. Event Name
                         *  -------------
                         */
                        esc_attr( $event_data[ 'event_list' ] )
                );

                /**
                 *  Load Content
                 *  ------------
                 */
                self:: load_tab_content( $event_data );


                ?></div></div></div><?php
            }
        }

        /**
         *  Tab Content
         *  -----------
         */
        public static function load_tab_content( $get_event_data = [] ){

            /**
             *  Make sure it's not empty!
             *  -------------------------
             */
            if( parent:: _is_array( $get_event_data ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( $get_event_data );

                ?>

                <div class="row">

                    <div class="col-md-7">
                    <?php

                        /**
                         *  Invitation
                         *  ----------
                         */
                        self:: guest_age( $get_event_data );

                        /**
                         *  Invitation
                         *  ----------
                         */
                        self:: invitation( $get_event_data );

                        /**
                         *  Guest Group
                         *  -----------
                         */
                        self:: group( $get_event_data );                        

                        /**
                         *  Event Meal
                         *  ----------
                         */
                        self:: event_meal( $get_event_data );

                    ?>
                    </div>

                    <div class="col-md-5">
                        
                        <?php self:: chart( $get_event_data ); ?>

                    </div>

                </div>
                <?php
            }
        }

        /**
         *  Graph
         *  -----
         */
        public static function event_graph( $event_id = '' ){

            /**
             *  Event ID
             *  --------
             */
            $event_unique_id    =   empty( $event_id )  

                                ?   absint( $_GET[ 'event-id' ] ) 

                                :   $event_id;

            $guest_list         =   parent:: guest_list();

            /**
             *  Collection
             *  ----------
             */
            return      [

                /**
                 *  Total Guest
                 *  -----------
                 */
                'total_guest'       =>  absint( count(  $guest_list ) ),

                /**
                 *  Graph
                 *  -----
                 */
                'chart_graph'       =>  array(

                    /**
                     *  Attended
                     *  --------
                     */
                    'Attending'     =>  array(

                        'class'         =>   sanitize_html_class( 'bg-success' ),

                        'count_guest'   =>  ( absint( parent:: get_event_counter( array(

                                                /**
                                                 *  Event Unique ID
                                                 *  ---------------
                                                 */
                                                'event_unique_id'   =>  absint( $event_unique_id ),

                                                /**
                                                 *  Find Key
                                                 *  --------
                                                 */
                                                '__FIND__'          =>  [

                                                    'guest_invited' =>  absint( '2' )
                                                ]

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
                                                    'event_unique_id'   =>  absint( $event_unique_id ),

                                                    /**
                                                     *  Find Key
                                                     *  --------
                                                     */
                                                    '__FIND__'          =>  [
                                                            
                                                        'guest_invited' =>  absint( '2' )
                                                    ]

                                                ) ) ) * absint( '100' )  ) 

                                              /  absint( absint( count(  $guest_list  )  ) ),

                                              /**
                                               *  2. pointer
                                               *  ----------
                                               */

                                              absint( '1' )

                                            ),
                    ),
                                        
                    /**
                     *  Pending
                     *  -------
                     */
                    'Pending'       =>  array(

                        'class'         =>   sanitize_html_class( 'bg-warning' ),

                        'count_guest'   =>  ( absint( parent:: get_event_counter( array(

                                                /**
                                                 *  Event Unique ID
                                                 *  ---------------
                                                 */
                                                'event_unique_id'   =>  absint( $event_unique_id ),

                                                /**
                                                 *  Find Key
                                                 *  --------
                                                 */
                                                '__FIND__'          =>  [
                                                        
                                                    'guest_invited' =>  absint( '1' )
                                                ]

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
                                                    'event_unique_id'   =>  absint( $event_unique_id ),

                                                    /**
                                                     *  Find Key
                                                     *  --------
                                                     */
                                                    '__FIND__'          =>  [
                                                            
                                                        'guest_invited' =>  absint( '1' )
                                                    ]

                                                ) ) ) * absint( '100' )  ) 

                                              /  absint( absint( count(  $guest_list  )  ) ),

                                              /**
                                               *  2. pointer
                                               *  ----------
                                               */

                                              absint( '1' )

                                            ),
                    ),

                    /**
                     *  Canceled
                     *  --------
                     */
                    'Canceled'      =>  array(

                        'class'         =>   sanitize_html_class( 'bg-danger' ),

                        'count_guest'   =>  ( absint( parent:: get_event_counter( array(

                                                /**
                                                 *  Event Unique ID
                                                 *  ---------------
                                                 */
                                                'event_unique_id'   =>  absint( $event_unique_id ),

                                                /**
                                                 *  Find Key
                                                 *  --------
                                                 */
                                                '__FIND__'          =>  [
                                                        
                                                    'guest_invited' =>  absint( '3' )
                                                ]

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
                                                    'event_unique_id'   =>  absint( $event_unique_id ),

                                                    /**
                                                     *  Find Key
                                                     *  --------
                                                     */
                                                    '__FIND__'          =>  [
                                                            
                                                        'guest_invited' =>  absint( '3' )
                                                    ]

                                                ) ) ) * absint( '100' )  ) 

                                              /  absint( absint( count(  $guest_list  )  ) ),

                                              /**
                                               *  2. pointer
                                               *  ----------
                                               */

                                              absint( '1' )

                                            ),
                    ),
                ),
            ];
        }

        /**
         *  Graph
         *  -----
         */
        public static function chart( $event_data = [] ){

            /**
             *  Is Array ?
             *  ----------
             */
            if( parent:: _is_array( $event_data ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( $event_data );

                /**
                 *  Extract
                 *  -------
                 */
                extract( self:: event_graph() );

                /**
                 *  Var
                 *  ---
                 */
                $_collection    =   '';

                $_string        =   $_counter     =   [];

                /**
                 *  Graph
                 *  -----
                 */
                if( parent:: _is_array( $chart_graph ) ){

                    /**
                     *  Loop
                     *  ----                     
                     */
                    foreach( $chart_graph as $key => $value ){

                        /**
                         *  Extract
                         *  -------
                         */
                        extract( $value );

                        $_string[]      =   $key;

                        $_counter[]     =   $percentage;

                        /**
                         *  Div
                         *  ---
                         */
                        $_collection    .=

                        sprintf( '<div class="d-flex align-items-center">

                                    <div class="d-flex flex-column align-items-center">

                                        <p class="badge fs-5 %1$s me-2" id="%2$s_Guest">%4$s</p> 

                                        <p class="fs-4 mb-0">%3$s</p>

                                        <p>%2$s</p>

                                    </div>

                                </div>',

                                /**
                                 *  1. Class
                                 *  --------
                                 */
                                $class,

                                /**
                                 *  2. Name
                                 *  -------
                                 */
                                $key,

                                /**
                                 *  3. Counter
                                 *  ----------
                                 */
                                $count_guest,

                                /**
                                 *  4. Percentage
                                 *  -------------
                                 */
                                $percentage . '%'
                        );
                    }
                }

                ?>
                <div class="card-shadow">
                    
                    <div class="card-shadow-header">

                        <h3 class="mb-0"><i class="fa fa-pie-chart" aria-hidden="true"></i><?php esc_attr_e( 'Attendance', 'sdweddingdirectory-guest-list' ); ?></h3>

                    </div>

                    <div class="card-shadow-body pb-0">
                    <?php

                        printf( '<div id="sdweddingdirectory_event_chart" data-total-guest="%1$s" data-lable="%2$s" data-counter="%3$s"></div>

                                <div class="text-muted">

                                    <div class="d-flex justify-content-around align-items-center">%4$s</div>

                                </div>',

                                /**
                                 *  1. Total Guest
                                 *  --------------
                                 */
                                count( parent:: guest_list() ),

                                /**
                                 *  2. label
                                 *  --------
                                 */
                                esc_html( json_encode( $_string ) ),

                                /**
                                 *  2. Counter
                                 *  ----------
                                 */
                                esc_html( json_encode( $_counter ) ),

                                /**
                                 *  3. Collection
                                 *  -------------
                                 */
                                $_collection

                        );
                    ?>
                    </div>

                </div>
                <?php
            }
        }

        /**
         *  Meal
         *  ----
         */
        public static function event_meal( $event_data = [] ){

            /**
             *  Is Array ?
             *  ----------
             */
            if( parent:: _is_array( $event_data ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( $event_data );

                ?>
                <div class="card-shadow">
                    
                    <div class="card-shadow-header">

                        <h3 class="mb-0"><i class="fa fa-cutlery me-2" aria-hidden="true"></i><?php esc_attr_e( 'Menu', 'sdweddingdirectory-guest-list' ); ?></h3>

                    </div>

                    <?php

                    $collection     =   [];

                    /**
                     *  Event Meal
                     *  ----------
                     */
                    $event_meal    =   json_decode(

                                            parent:: get_event_details( array(

                                                    'event_unique_id'   =>  absint( $event_unique_id ),

                                                    'get_value'         =>  esc_attr( 'event_meal' )
                                            ) ),

                                            true
                                        );
                    /**
                     *  Have Menu ?
                     *  -----------
                     */
                    if( parent:: _is_array( $event_meal ) ){

                        /**
                         *  In Loop
                         *  -------
                         */
                        foreach( $event_meal as $key => $value ){

                            /**
                             *  Extract
                             *  -------
                             */
                            extract( $value );

                            /**
                             *  Collection
                             *  ----------
                             */
                            $collection[]       =       [

                                'slug'          =>      sanitize_title( $menu_list ),

                                'name'          =>      esc_attr( $menu_list ),

                                'counter'       =>      absint( parent:: get_event_counter( array(

                                                            /**
                                                             *  Event Unique ID
                                                             *  ---------------
                                                             */
                                                            'event_unique_id'   =>  absint( $event_unique_id ),

                                                            /**
                                                             *  Find Key
                                                             *  --------
                                                             */
                                                            '__FIND__'          =>  array(
                                                                    
                                                                'meal'      =>   sanitize_title( $menu_list )
                                                            )

                                                        ) ) )
                            ];
                        }
                    }

                    /**
                     *  Have Collection ?
                     *  -----------------
                     */
                    if( parent:: _is_array( $collection ) ){

                        ?><div class="card-shadow-body"><ul class="list-group"><?php

                        foreach( $collection as $key => $value ){

                            /**
                             *  Extract
                             *  -------
                             */
                            extract( $value );

                            /**
                             *  Column
                             *  ------
                             */
                            printf( '<li class="list-group-item d-flex justify-content-between align-items-center">

                                        <span>%1$s</span>

                                        <span class="badge bg-primary rounded-pill">%2$s</span>

                                    </li>',

                                    /**
                                     *  1. Name
                                     *  -------
                                     */
                                    esc_attr( $name ),

                                    /**
                                     *  2. Counter
                                     *  ----------
                                     */
                                    absint( $counter )
                            );
                        }

                        ?></ul></div><?php
                    }

                    ?>

                </div>
                <?php
            }
        }

        /**
         *  Invitation
         *  ----------
         */
        public static function invitation( $event_data = [] ){

            /**
             *  Is Array ?
             *  ----------
             */
            if( parent:: _is_array( $event_data ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( $event_data );

                /**
                 *  Guest List
                 *  ----------
                 */
                $guest_list         =       parent:: guest_list();
                
                ?>
                <div class="card-shadow">
                    
                    <div class="card-shadow-header">

                        <h3 class="mb-0"><i class="fa fa-envelope" aria-hidden="true"></i><?php esc_attr_e( 'Invitations', 'sdweddingdirectory-guest-list' ); ?></h3>

                    </div>
                    
                    <div class="card-shadow-body pb-0"><div class="row"><?php

                        /**
                         *  Column
                         *  ------
                         */
                        printf( '<div class="col-md-6 col-12">

                                    <p class="fw-bold fs-3 mb-1">

                                        <i class="fa fa-check-square-o text-success" aria-hidden="true"></i> 

                                        <span class="fs-3">%1$s</span>

                                    </p>

                                    <p class="text-muted">%2$s</p>

                                </div>

                                <div class="col-md-6 col-12">

                                    <p class="fw-bold fs-3 mb-1">

                                        <i class="fa fa fa-check-square-o text-warning" aria-hidden="true"></i> 

                                        <span class="fs-3">%3$s</span>

                                    </p>

                                    <p class="text-muted">%4$s</p>

                                </div>', 

                                /**
                                 *  1. Sending Request
                                 *  ------------------
                                 */
                                absint( parent:: get_event_counter( array(

                                    /**
                                     *  Event Unique ID
                                     *  ---------------
                                     */
                                    'event_unique_id'   =>  absint( $event_unique_id ),

                                    /**
                                     *  Find Key
                                     *  --------
                                     */
                                    '__FIND__'          =>  [

                                        'guest_invited' =>  absint( '1' )
                                    ]

                                ) ) ),

                                /**
                                 *  2. Translation Ready String
                                 *  ---------------------------
                                 */
                                esc_attr__( 'Invitations sent', 'sdweddingdirectory-guest-list' ),

                                /**
                                 *  3. Pending Request
                                 *  ------------------
                                 */
                                absint( parent:: get_event_counter( array(

                                    /**
                                     *  Event Unique ID
                                     *  ---------------
                                     */
                                    'event_unique_id'   =>  absint( $event_unique_id ),

                                    /**
                                     *  Find Key
                                     *  --------
                                     */
                                    '__FIND__'          =>  [

                                        'guest_invited' =>  absint( '0' )
                                    ]

                                ) ) ),

                                /**
                                 *  4. Translation Ready String
                                 *  ---------------------------
                                 */
                                esc_attr__( 'Not sent', 'sdweddingdirectory-guest-list' )
                        );

                    ?></div></div>

                </div>
                <?php
            }
        }

        /**
         *  Group
         *  -----
         */
        public static function group( $event_data = [] ){

            /**
             *  Is Array ?
             *  ----------
             */
            if( parent:: _is_array( $event_data ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( $event_data );

                /**
                 *  Get Group
                 *  ---------
                 */
                $group              =       parent:: group_list();

                /**
                 *  Guest List
                 *  ----------
                 */
                $guest_list         =       parent:: guest_list();
                
                ?>
                <div class="card-shadow">
                    
                    <div class="card-shadow-header">

                        <h3 class="mb-0"><i class="fa fa-users" aria-hidden="true"></i><?php esc_attr_e( 'Groups', 'sdweddingdirectory-guest-list' ); ?></h3>

                    </div>
                    <?php

                    $collection     =   [];

                    /**
                     *  Have Menu ?
                     *  -----------
                     */
                    if( parent:: _is_array( $group ) && parent:: _is_array( $guest_list ) ){

                        /**
                         *  In Loop
                         *  -------
                         */
                        foreach( $group as $key => $value ){

                            /**
                             *  Extract
                             *  -------
                             */
                            extract( $value );

                            /**
                             *  Collection
                             *  ----------
                             */
                            $collection[]       =       [

                                'slug'          =>      sanitize_title( $group_name ),

                                'name'          =>      esc_attr( $group_name ),

                                'counter'       =>      absint( parent:: get_counter_counter( [

                                                            /**
                                                             *  Find Key
                                                             *  --------
                                                             */
                                                            '__FIND__'          =>  array(
                                                                    
                                                                'guest_group'      =>   sanitize_title( $group_name )
                                                            )

                                                        ] ) )
                            ];
                        }
                    }

                    /**
                     *  Have Collection ?
                     *  -----------------
                     */
                    if( parent:: _is_array( $collection ) ){

                        ?><div class="card-shadow-body pb-0"><div class="row"><?php

                        foreach( $collection as $key => $value ){

                            /**
                             *  Extract
                             *  -------
                             */
                            extract( $value );

                            /**
                             *  Column
                             *  ------
                             */
                            printf( '<div class="col-md-3 col-12">

                                        <p class="fw-bold mb-1">%1$s</p>

                                        <p class="text-muted">%2$s %3$s</p>

                                    </div>

                                    <div class="col-md-9 col-12">

                                        <div class="progress">

                                            <div    class="progress-bar" role="progressbar"

                                                    style="width: %4$s; background-color:%6$s;" aria-valuenow="%4$s 

                                                    aria-valuemin="0" aria-valuemax="%5$s"></div>

                                        </div>

                                    </div>', 

                                    /**
                                     *  1. Name
                                     *  -------
                                     */
                                    esc_attr( $name ),

                                    /**
                                     *  2. Counter
                                     *  ----------
                                     */
                                    absint( $counter ),

                                    /**
                                     *  3. Translation Ready String
                                     *  ---------------------------
                                     */
                                    esc_attr__( 'guests', 'sdweddingdirectory-guest-list' ),

                                    /**
                                     *  4. Get Percentage
                                     *  -----------------
                                     */
                                    absint( $counter ) * 100 / count( $guest_list ) . '%',

                                    /**
                                     *  5. Total Guest
                                     *  --------------
                                     */
                                    count( $guest_list ),

                                    /**
                                     *  6. Style
                                     *  --------
                                     */
                                    sprintf( "#%06X\n", mt_rand( 0, 0xFFFFFF ))
                            );
                        }

                        ?></div></div><?php
                    }

                    ?>

                </div>
                <?php
            }
        }

        /**
         *  Invitation
         *  ----------
         */
        public static function guest_age( $event_data = [] ){

            /**
             *  Is Array ?
             *  ----------
             */
            if( parent:: _is_array( $event_data ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( $event_data );

                /**
                 *  Guest List
                 *  ----------
                 */
                $guest_list         =       parent:: guest_list();

                $age                =       [   
                                                'adult'     =>  esc_attr__( 'Adult', 'sdweddingdirectory-guest-list' ),

                                                'child'     =>  esc_attr__( 'Child', 'sdweddingdirectory-guest-list' ),

                                                'baby'      =>  esc_attr__( 'Baby', 'sdweddingdirectory-guest-list' ),
                                            ];
                
                ?>
                <div class="card-shadow">
                    
                    <div class="card-shadow-header">

                        <h3 class="mb-0"><i class="fa fa-user" aria-hidden="true"></i><?php esc_attr_e( 'Guests', 'sdweddingdirectory-guest-list' ); ?></h3>

                    </div>

                    <?php

                    $collection     =   [];

                    /**
                     *  Have Menu ?
                     *  -----------
                     */
                    if( parent:: _is_array( $age ) && parent:: _is_array( $guest_list ) ){

                        /**
                         *  In Loop
                         *  -------
                         */
                        foreach( $age as $key => $value ){

                            /**
                             *  Collection
                             *  ----------
                             */
                            $collection[]       =       [

                                'slug'          =>      sanitize_title( $key ),

                                'name'          =>      esc_attr( $value ),

                                'counter'       =>      absint( parent:: get_counter_counter( [

                                                            /**
                                                             *  Find Key
                                                             *  --------
                                                             */
                                                            '__FIND__'          =>  array(
                                                                    
                                                                'guest_age'      =>   sanitize_title( $key )
                                                            )

                                                        ] ) )
                            ];
                        }
                    }

                    /**
                     *  Have Collection ?
                     *  -----------------
                     */
                    if( parent:: _is_array( $collection ) ){

                        ?><div class="card-shadow-body pb-0"><div class="row"><?php

                        foreach( $collection as $key => $value ){

                            /**
                             *  Extract
                             *  -------
                             */
                            extract( $value );

                            /**
                             *  Column
                             *  ------
                             */
                            printf( '<div class="col-md-3 col-12">

                                        <p><span class="fw-bold">%2$s</span> <span class="text-muted">%1$s</span></p>

                                    </div>

                                    <div class="col-md-9 col-12">

                                        <div class="progress">

                                            <div    class="progress-bar" role="progressbar"

                                                    style="width: %3$s; background-color:%5$s;" aria-valuenow="%3$s 

                                                    aria-valuemin="0" aria-valuemax="%4$s"></div>

                                        </div>

                                    </div>', 

                                    /**
                                     *  1. Name
                                     *  -------
                                     */
                                    esc_attr( $name ),

                                    /**
                                     *  2. Counter
                                     *  ----------
                                     */
                                    absint( $counter ),

                                    /**
                                     *  3. Get Percentage
                                     *  -----------------
                                     */
                                    absint( $counter ) * 100 / count( $guest_list ) . '%',

                                    /**
                                     *  4. Total Guest
                                     *  --------------
                                     */
                                    count( $guest_list ),

                                    /**
                                     *  5. Style
                                     *  --------
                                     */
                                    sprintf( "#%06X\n", mt_rand( 0, 0xFFFFFF ))
                            );
                        }

                        ?></div></div><?php
                    }

                    ?>

                </div>
                <?php
            }
        }

    }

    /**
     *  SDWeddingDirectory Couple Guest List Statistic
     *  --------------------------------------
     */
    SDWeddingDirectory_Dashboard_Guest_List_Statistic:: get_instance();
}