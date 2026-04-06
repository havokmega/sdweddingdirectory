<?php
/**
 *  SDWeddingDirectory Request Quote Dashboard Page
 *  ---------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Dashboard_Request_Quote' ) && class_exists( 'SDWeddingDirectory_Request_Quote_Database' ) ){

    /**
     *  SDWeddingDirectory Request Quote Dashboard Page
     *  ---------------------------------------
     */
    class SDWeddingDirectory_Dashboard_Request_Quote extends SDWeddingDirectory_Request_Quote_Database{

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
             *  Is Vendor and on reqest quote page
             *  ----------------------------------
             */
            if( parent:: is_vendor() && parent:: dashboard_page_set( esc_attr( 'vendor-request-quote' ) ) ){

                /**
                 *  1. Dashboard Scripts
                 *  --------------------
                 */
                add_action( 'wp_enqueue_scripts', [$this, 'sdweddingdirectory_script'], absint( '50' ) );

                /**
                 *  2. Dashboard Page
                 *  -----------------
                 */
                add_action( 'sdweddingdirectory/vendor-dashboard', [$this, 'dashboard_page'], absint( '50' ), absint( '1' ) );
            }
        }

        /**
         *  1. Load Script
         *  --------------
         */
        public static function sdweddingdirectory_script(){

            /**
             *  Vendor Dashboard - Request Quote Page
             *  -------------------------------------
             */
            if( parent:: dashboard_page_set( 'vendor-request-quote' ) ){

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
                if( ! empty( $page ) && $page == esc_attr( 'vendor-request-quote' )  ){

                    ?><div class="container"><?php

                        /**
                         *  Dashboard Title
                         *  ---------------
                         */
                        SDWeddingDirectory_Dashboard:: dashboard_page_header(

                            /**
                             *  Translation Ready String
                             *  ------------------------
                             */
                            esc_attr__( 'Quote Requests', 'sdweddingdirectory' )
                        );

                        /**
                         *  Daily Booking Capacity Setting
                         *  ------------------------------
                         */
                        self:: render_capacity_setting();

                        /**
                         *  Dashboard Content
                         *  -----------------
                         */
                        self:: dashboard_content();

                    ?></div><?php
                }
            }
        }

        /**
         *  Content
         *  -------
         */
        public static function dashboard_content(){

            /**
             *  Extract Args
             *  ------------
             */
            extract( [

                'tab'                   =>      [],

                'tab_content'           =>      [],

                'vendor_id'             =>      absint( parent:: post_id() ),

                'handler'               =>      []

            ] );

            /**
             *  WP_Query
             *  --------
             */
            $query          =       new  WP_Query( array(

                                        'post_type'         =>  [ esc_attr( 'venue' ), esc_attr( 'vendor' ) ],

                                        'post_status'       =>  esc_attr( 'publish' ), 

                                        'posts_per_page'    =>  -1,

                                        'author'            =>  absint( parent:: author_id() )

                                    ) );

            /**
             *  Have Post ?
             *  -----------
             */
            if( $query->have_posts() ){
             
                /**
                 *  In Loop
                 *  -------
                 */
                while ( $query->have_posts() ){  $query->the_post();

                    /**
                     *  Post ID
                     *  -------
                     */
                    $post_id                =       absint( get_the_ID() );

                    $tab_id                 =       esc_attr( parent:: _rand() );

                    $tab                    =       parent:: get_request_tab_data( [

                                                        'venue_id'        =>      absint( $post_id ),

                                                        'vendor_id'         =>      absint( $vendor_id ),

                                                        'unique_id'         =>      esc_attr( $tab_id ),

                                                        'get_data'          =>      esc_attr( 'tabs' ),

                                                    ] );

                    $tab_content            =       parent:: get_request_tab_data( [

                                                        'venue_id'        =>      absint( $post_id ),

                                                        'vendor_id'         =>      absint( $vendor_id ),

                                                        'unique_id'         =>      esc_attr( $tab_id ),

                                                        'get_data'          =>      esc_attr( 'tabs_content' ),

                                                    ] );

                    /**
                     *  Make sure tab + content have data
                     *  ---------------------------------
                     */
                    if( ! empty( $tab ) && ! empty( $tab_content ) ){

                        $handler[]      =       [

                            'post_id'           =>      $post_id,

                            'tab'               =>      $tab,

                            'tab_content'       =>      $tab_content,

                            'unique_id'         =>      $tab_id
                        ];
                    }
                }

                /**
                 *  Reset Filter
                 *  ------------
                 */
                if ( isset( $query ) ) {
                    
                    $query->reset_postdata();
                }
            }

            /**
             *  Get Data After Load Dashboard Setup
             *  -----------------------------------
             */
            if( parent:: _is_array( $handler ) ){

                /**
                 *  Load Frame
                 *  ----------
                 */
                self:: request_quote_page_setup( $handler );
            }
        }

        /**
         *  Display Review Options
         *  ----------------------
         */
        public static function request_quote_page_setup( $args = [] ){

            /**
             *  Is Array ?
             *  ----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'select_options'        =>      [],

                    'tab'                   =>      absint( '0' ),

                    'tab_counter'           =>      absint( '0' )

                ] ) );

                ?>
                <div class="card-shadow">

                    <div class="card-shadow-body p-0">

                        <div class="d-flex justify-content-between row border-bottom p-4">

                            <div class="col-md-4">

                                <div class="search-review-list"><i class="fa fa-search"></i>
                                <?php

                                    /**
                                     *  Translation Ready String
                                     *  ------------------------
                                     */
                                    printf( '<input autocomplete="off" type="text" id="%1$s" class="form-control form-dark form-control-sm" placeholder="%2$s" />',

                                        /**
                                         *  1. Search Input ID
                                         *  ------------------
                                         */
                                        esc_attr( 'sdweddingdirectory-request-search' ),

                                        /**
                                         *  2. Translation String
                                         *  ---------------------
                                         */
                                        esc_attr__( 'Search list', 'sdweddingdirectory-request-quote' )
                                    );

                                ?>
                                </div>

                            </div>

                            <div class="col-md-4 mt-3 mt-md-0">
                            <?php

                                /**
                                 *  Collected Dropdown Item
                                 *  -----------------------
                                 */
                                foreach ( $args as $key => $value ) {

                                    /**
                                     *  Extract
                                     *  -------
                                     */
                                    extract( $value );

                                    /**
                                     *  Dropdown
                                     *  --------
                                     */
                                    $select_options[ $post_id ]     =   sprintf( '<option value="%1$s">%2$s</option>',

                                                                            /**
                                                                             *  1. Value
                                                                             *  ---------
                                                                             */
                                                                            esc_attr( $unique_id .'-'. absint( $post_id ) ),

                                                                            /**
                                                                             *  2. Name
                                                                             *  -------
                                                                             */
                                                                            esc_attr( get_the_title( $post_id ) )
                                                                        );
                                }

                                /**
                                 *   Create Select Options
                                 *   ---------------------
                                 */
                                if( parent:: _is_array( $select_options ) ){

                                    printf( '<select class="sdweddingdirectory-dark-select %2$s" name="%2$s">%1$s</select>', 

                                            /**
                                             *  1. Options
                                             *  ----------
                                             */
                                            implode( '', $select_options ),

                                            /**
                                             *  2. Select Option Name + ID
                                             *  --------------------------
                                             */
                                            esc_attr( 'sdweddingdirectory-select-tab' )
                                    );
                                }

                            ?>
                            </div>

                            <div class="col-md-4 mt-3 mt-md-0">
                                <?php
                                    $status_filter_options = '<option value="">' . esc_html__( 'All Statuses', 'sdweddingdirectory' ) . '</option>';

                                    foreach( parent:: lead_status_options() as $status_key => $status_label ){
                                        $status_filter_options .= sprintf( '<option value="%s">%s</option>', esc_attr( $status_key ), esc_html( $status_label ) );
                                    }

                                    printf( '<select class="sdweddingdirectory-dark-select" id="sdwd-status-filter" name="sdwd-status-filter">%s</select>',
                                        $status_filter_options
                                    );
                                ?>
                            </div>

                        </div>

                    </div>

                    <ul class="nav nav-pills mb-3 horizontal-tab-second justify-content-center nav-fill my-venue-tab d-none" role="tablist"><?php

                        /**
                         *  Create Tab
                         *  ----------
                         */
                        foreach ( $args as $key => $value ) {

                            /**
                             *  Extract
                             *  -------
                             */
                            extract( $value );

                            /**
                             *  List
                             *  ----
                             */
                            printf(     '<li class="nav-item">

                                            <a  class="nav-link %1$s"

                                                data-bs-toggle="tab" aria-selected="true" role="tab" 

                                                id="%2$s" href="#%2$s-tab" aria-controls="%2$s-tab">%3$s</a>

                                        </li>',

                                        /**
                                         *  1. Is Active ?
                                         *  --------------
                                         */
                                        $tab == absint( '0' )  ?  sanitize_html_class( 'active' )  :  '',

                                        /**
                                         *  2. Unique ID
                                         *  ------------
                                         */
                                        esc_attr( $unique_id . '-' . $post_id ),

                                        /**
                                         *  3. Name of Tab
                                         *  --------------
                                         */
                                        esc_attr( get_the_title( absint( $post_id ) ) )
                            );

                            $tab++;
                        }

                    ?>
                    </ul>

                    <div class="tab-content" id="sdweddingdirectory-request-showcase">
                    <?php

                        /**
                         *  Create Tab
                         *  ----------
                         */
                        foreach ( $args as $key => $value ) {

                            /**
                             *  Extract
                             *  -------
                             */
                            extract( wp_parse_args( $value, [

                                'rand_id'       =>      esc_attr( parent:: _rand() )

                            ] ) );

                            /**
                             *  Tab Content
                             *  -----------
                             */
                            printf(     '<div class="tab-pane fade %1$s" id="%2$s-%3$s-tab" role="tabpanel" aria-labelledby="%2$s-%3$s">',

                                        /**
                                         *  1. Is Active ?
                                         *  --------------
                                         */
                                        $tab_counter == absint( '0' )  ?  esc_attr( 'active show' )  :  '',

                                        /**
                                         *  2. Unique ID
                                         *  ------------
                                         */
                                        esc_attr( $unique_id ),

                                        /**
                                         *  3. Post ID
                                         *  ----------
                                         */
                                        absint( $post_id )
                            );

                            ?>
                            <div class="card-shadow-body p-0">

                                <div class="row g-0">

                                    <div class="col-md-4">

                                        <div class="reviews-tabbing-wrap">
                                        <?php

                                            printf( '<div   class="nav flex-column nav-pills theme-tabbing-vertical reviews-tabbing" 

                                                            id="%1$s" role="tablist" aria-orientation="vertical">%2$s</div>',

                                                    /**
                                                     *  1. Random ID
                                                     *  ------------
                                                     */
                                                    $rand_id,

                                                    /**
                                                     *  2. Tab
                                                     *  ------
                                                     */
                                                    $tab
                                            );

                                        ?>
                                        </div>

                                    </div>

                                    <div class="col-md-8">
                                    <?php

                                        printf( '<div class="tab-content">%2$s</div>',

                                                /**
                                                 *  1. Random ID
                                                 *  ------------
                                                 */
                                                $rand_id,

                                                /**
                                                 *  2. Tab
                                                 *  ------
                                                 */
                                                $tab_content
                                        );
                                    ?>
                                    </div>

                                </div>

                            </div>
                            <?php

                            /**
                             *  Tab Content
                             *  -----------
                             */
                            ?></div><?php   $tab_counter++;
                        }

                    ?>
                    </div>

                </div>
                <?php
            }
        }
        /**
         *  Render Capacity Setting
         *  -----------------------
         */
        public static function render_capacity_setting(){

            $vendor_id = absint( parent:: post_id() );

            if( empty( $vendor_id ) ){
                return;
            }

            $capacity = get_post_meta( $vendor_id, sanitize_key( 'sdwd_daily_booking_capacity' ), true );
            $capacity = ! empty( $capacity ) ? absint( $capacity ) : 1;

            $nonce = wp_create_nonce( 'sdwd_update_capacity_' . $vendor_id );

            ?>
            <div class="card-shadow mb-3">
                <div class="card-shadow-body py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <strong class="small"><?php esc_html_e( 'Daily Booking Capacity', 'sdweddingdirectory' ); ?></strong>
                            <p class="text-muted small mb-0"><?php esc_html_e( 'Maximum events you can book per day. Set to 1 for exclusive-date vendors.', 'sdweddingdirectory' ); ?></p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <input type="number" class="form-control form-control-sm" id="sdwd-booking-capacity"
                                   value="<?php echo absint( $capacity ); ?>" min="1" max="99" style="width:70px;"
                                   data-vendor-id="<?php echo absint( $vendor_id ); ?>"
                                   data-nonce="<?php echo esc_attr( $nonce ); ?>" />
                            <button type="button" class="btn btn-sm btn-default" id="sdwd-save-capacity">
                                <i class="fa fa-check"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }

    /**
     *  Request Quote Obj Call
     *  ----------------------
     */
    SDWeddingDirectory_Dashboard_Request_Quote:: get_instance();
}
