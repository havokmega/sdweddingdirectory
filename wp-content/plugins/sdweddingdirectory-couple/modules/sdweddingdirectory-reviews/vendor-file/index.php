<?php
/**
 *  SDWeddingDirectory - Vendor Reviews Page
 *  --------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Dashboard_Review' ) && class_exists( 'SDWeddingDirectory_Reviews_Database' ) ){

    /**
     *  SDWeddingDirectory - Vendor Reviews Page
     *  --------------------------------
     */
    class SDWeddingDirectory_Vendor_Dashboard_Review extends SDWeddingDirectory_Reviews_Database{

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
             *  Is Vendor ?
             *  -----------
             */
            if( parent:: is_vendor() && parent:: dashboard_page_set( esc_attr( 'vendor-reviews' ) ) ){

                /**
                 *  1. Dashboard Scripts
                 *  --------------------
                 */
                add_action( 'wp_enqueue_scripts', [$this, 'sdweddingdirectory_script'], absint( '60' ) );

                /**
                 *  2. Dashboard Page
                 *  -----------------
                 */
                add_action( 'sdweddingdirectory/vendor-dashboard', [$this, 'dashboard_page'], absint( '60' ), absint( '1' ) );
            }
        }

        /**
         *  1. Load Script
         *  --------------
         */
        public static function sdweddingdirectory_script(){

            /**
             *  If is vendor review dashboard OR vendor singular page
             *  -----------------------------------------------------
             */
            if( parent:: dashboard_page_set( 'vendor-reviews' ) ){

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

                /**
                 *  Load Library Condition
                 *  -----------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/review', function( $args = [] ){

                    return  array_merge( $args, [ 'vendor-reviews'   =>  true ] );
                    
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
                if( ! empty( $page ) && $page == esc_attr( 'vendor-reviews' )  ){

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
                            esc_attr__( 'My Reviews', 'sdweddingdirectory-reviews' )
                        );

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
         *  Page Content
         *  ------------
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

                                        'post_type'         =>  esc_attr( 'venue' ),

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

                    $collection             =       parent:: get_review_tab_data( [

                                                        'venue_id'        =>      absint( $post_id ),

                                                        'vendor_id'         =>      absint( $vendor_id ),

                                                    ] );
                    /**
                     *  Found Tab ?
                     *  -----------
                     */
                    if( parent:: _is_array( $collection ) ){

                        $tab                    =       implode( '', $collection[ 'tab' ] );

                        $tab_content            =       implode( '', $collection[ 'tab_content' ] );

                        $has_summary            =       parent:: get_venue_review( [

                                                            'venue_id'        =>      absint( $post_id )

                                                        ] );
                    }

                    /**
                     *  Make sure tab + content have data
                     *  ---------------------------------
                     */
                    if( ! empty( $tab ) && ! empty( $tab_content ) && ! empty( $tab_summary ) ){

                        $handler[]      =       [

                            'post_id'           =>      $post_id,

                            'tab'               =>      $tab,

                            'tab_content'       =>      $tab_content,

                            'tab_summary'       =>      $tab_summary,

                            'unique_id'         =>      esc_attr( parent:: _rand() )
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
                self:: review_page_setup( $handler );
            }
        }

        /**
         *  Display Review Options
         *  ----------------------
         */
        public static function review_page_setup( $args = [] ){

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

                    'tab_counter'           =>      absint( '0' ),

                ] ) );

                ?>
                <div class="card-shadow">

                    <ul class="nav nav-pills mb-3 horizontal-tab-second justify-content-center nav-fill my-venue-tab d-none" role="tablist">
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
                             *  List
                             *  ----
                             */
                            printf(     '<li class="nav-item">

                                            <a  class="nav-link %1$s"

                                                data-bs-toggle="tab" aria-selected="true" role="tab" 

                                                id="%2$s" href="#%2$s-summary" aria-controls="%2$s-summary">%3$s</a>

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

                    <div class="tab-content">
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
                             *  Tab Content
                             *  -----------
                             */
                            printf(     '<div class="tab-pane fade %1$s" id="%2$s-%3$s-summary" role="tabpanel" aria-labelledby="%2$s-%3$s">%4$s</div>',

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
                                        absint( $post_id ),

                                        /**
                                         *  4. Get Summary Tab
                                         *  ------------------
                                         */
                                        $tab_summary
                            );

                            /**
                             *  Tab Rating
                             *  ----------
                             */
                            $tab_counter++;
                        }

                    ?>
                    </div>

                </div>

                <?php

                    /**
                     *  Extract
                     *  -------
                     */
                    extract( wp_parse_args( $args, [

                        'tab'                   =>      absint( '0' ),

                        'tab_counter'           =>      absint( '0' ),

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
                                        esc_attr( 'sdweddingdirectory-review-search' ),

                                        /**
                                         *  2. Translation String
                                         *  ---------------------
                                         */
                                        esc_attr__( 'Search list', 'sdweddingdirectory-reviews' )
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

                    <div class="tab-content" id="sdweddingdirectory-reviews-showcase">
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

                                'rand_id'       =>      esc_attr( parent:: _rand() ),

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
    }

    /**
     *  SDWeddingDirectory - Vendor Reviews Page
     *  --------------------------------
     */
    SDWeddingDirectory_Vendor_Dashboard_Review::get_instance();
}