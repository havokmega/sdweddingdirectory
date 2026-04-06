<?php
/**
 *  SDWeddingDirectory - Couple Reviews Page
 *  --------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Dashboard_Review' ) && class_exists( 'SDWeddingDirectory_Reviews_Database' ) ){

    /**
     *  SDWeddingDirectory - Couple Reviews Page
     *  --------------------------------
     */
    class SDWeddingDirectory_Couple_Dashboard_Review extends SDWeddingDirectory_Reviews_Database{

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
            add_action( 'wp_enqueue_scripts', [ $this, 'sdweddingdirectory_script' ], absint( '70' ) );

            /**
             *  2. Dashboard Page
             *  -----------------
             */
            add_action( 'sdweddingdirectory/couple-dashboard', [ $this, 'dashboard_page' ], absint( '70' ), absint( '1' ) );
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
            if( parent:: dashboard_page_set( 'couple-reviews' ) ){

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

                    return  array_merge( $args, [ 'couple-reviews'   =>  true ] );
                    
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
                if( ! empty( $page ) && $page == esc_attr( 'couple-reviews' )  ){

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

                'couple_id'             =>      absint( parent:: post_id() ),

                'handler'               =>      []

            ] );

            /**
             *  Get Post IDs
             *  ------------
             */
            $review_post_ids    =   apply_filters(  'sdweddingdirectory/post/data', [

                                        'post_type'         =>      esc_attr( 'venue-review' ),

                                    ] );
            /**
             *  Is Array ?
             *  ----------
             */
            $venues       =   [];

            $counter        =   absint( '0' );

            if(  parent:: _is_array( $review_post_ids )  ){

                foreach(  $review_post_ids as $rating_post_id => $post_title  ){

                    if( get_post_meta( $rating_post_id, sanitize_key( 'couple_id' ), true ) == $couple_id ){

                        /**
                         *  Post ID
                         *  -------
                         */
                        $venue_id     =       absint( get_post_meta( $rating_post_id, sanitize_key( 'venue_id' ), true ) );

                        $collection     =       parent:: get_venue_review_tab_data( [

                                                    'post_id'           =>      absint( $rating_post_id ),

                                                    'venue_id'        =>      absint( $venue_id ),

                                                    'vendor_id'         =>      absint( parent:: venue_author_post_id( $venue_id ) ),

                                                    'couple_id'         =>      absint( $couple_id ),

                                                    'active_tab'        =>      $counter == absint( '0' )  ? true : false

                                                ] );
                        /**
                         *  Is Array ?
                         *  ----------
                         */
                        if( parent:: _is_array( $collection ) ){

                            $tab                =       implode( '', $collection[ 'tabs' ] );

                            $tab_content        =       implode( '', $collection[ 'tab_content' ] );
                        }

                        /**
                         *  Make sure tab + content have data
                         *  ---------------------------------
                         */
                        if( ! empty( $tab ) && ! empty( $tab_content ) ){

                            $handler[ $venue_id ]     =       [

                                'post_id'               =>      $venue_id,

                                'tab'                   =>      $tab,

                                'tab_content'           =>      $tab_content,

                                'unique_id'             =>      esc_attr( parent:: _rand() )
                            ];
                        }

                        $counter++;
                    }
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

                ?>
                <div class="card-shadow">

                    <div class="tab-content" id="sdweddingdirectory-reviews-showcase">
                    <?php

                        /**
                         *  Extract
                         *  -------
                         */
                        extract( wp_parse_args( $args, [

                            'tab'                   =>      absint( '0' ),

                            'tab_counter'           =>      absint( '0' ),

                            'rand_id'               =>      esc_attr( parent:: _rand() ),

                        ] ) );

                        ?>
                        <div class="card-shadow-body p-0">

                            <div class="row g-0">

                                <div class="col-md-4">

                                    <div class="border-end border-bottom p-4">

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
                                                esc_attr__( 'Find Venue', 'sdweddingdirectory-reviews' )
                                            );

                                        ?>
                                        </div>

                                    </div>

                                    <div class="reviews-tabbing-wrap">
                                    <?php


                                        printf( '<div   class="nav flex-column nav-pills theme-tabbing-vertical reviews-tabbing" 

                                                        id="%1$s" role="tablist" aria-orientation="vertical">', 

                                                    $rand_id    
                                        );

                                        /**
                                         *  Create Tab
                                         *  ----------
                                         */
                                        foreach ( $args as $key => $value ) {

                                            /**
                                             *  Extract Args
                                             *  ------------
                                             */
                                            extract( $value );

                                            print   $tab;
                                        }


                                        print '</div>';

                                    ?>
                                    </div>

                                </div>

                                <div class="col-md-8">

                                    <div class="tab-content">
                                    <?php

                                        /**
                                         *  Create Tab
                                         *  ----------
                                         */
                                        foreach ( $args as $key => $value ) {

                                            /**
                                             *  Extract Args
                                             *  ------------
                                             */
                                            extract( $value );

                                            print   $tab_content;
                                        }

                                    ?>
                                    </div>

                                </div>

                            </div>



                        </div>
                        <?php



                        $tab_counter++;
                    ?>
                    </div>

                </div>
                <?php
            }
        }
    }

    /**
     *  SDWeddingDirectory - Couple Reviews Page
     *  --------------------------------
     */
    SDWeddingDirectory_Couple_Dashboard_Review::get_instance();
}