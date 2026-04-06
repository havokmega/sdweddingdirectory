<?php
/**
 *  SDWeddingDirectory Venue Reviews
 *  --------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Singular_Venue_Reviews' ) && class_exists( 'SDWeddingDirectory_Reviews_Database' ) ){

    /**
     *  SDWeddingDirectory Venue Reviews
     *  --------------------------
     */
    class SDWeddingDirectory_Singular_Venue_Reviews extends SDWeddingDirectory_Reviews_Database{

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
             *  1. Load Script
             *  --------------
             */
            add_action( 'wp_enqueue_scripts', [$this, 'sdweddingdirectory_script'] );

            /**
             *  2. Venue Singular Page : Review Average Widget
             *  ------------------------------------------------
             */
            add_action( 'sdweddingdirectory/venue/singular/left-side/widget', [ $this, 'sdweddingdirectory_review_average' ], absint( '50' ), absint( '1' ) );

            /**
             *  3. Venue Singular Page : Review Submit Form Widget
             *  ----------------------------------------------------
             */
            add_action( 'sdweddingdirectory/venue/singular/left-side/widget', [ $this, 'sdweddingdirectory_submit_review' ], absint( '60' ), absint( '1' ) );

            /**
             *  2. Vendor Singular Page : Review Average Widget
             *  ------------------------------------------------
             */
            add_action( 'sdweddingdirectory/vendor/singular/left-side/widget', [ $this, 'sdweddingdirectory_review_average' ], absint( '50' ), absint( '1' ) );

            /**
             *  3. Vendor Singular Page : Review Submit Form Widget
             *  ---------------------------------------------------
             */
            add_action( 'sdweddingdirectory/vendor/singular/left-side/widget', [ $this, 'sdweddingdirectory_submit_review' ], absint( '60' ), absint( '1' ) );

            /**
             *  4. Venue Review Summary
             *  -------------------------
             */
            add_action( 'sdweddingdirectory/venue/header/left/overview', [ $this, 'venue_header_left_review' ], absint( '10' ), absint( '1' ) );

            /**
             *  4. Vendor Review Summary
             *  ------------------------
             */
            add_action( 'sdweddingdirectory/vendor/header/left/overview', [ $this, 'venue_header_left_review' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Review Script
         *  -------------
         */
        public static function sdweddingdirectory_script(){

            $_condition_1   =   is_singular( 'venue' );

            $_condition_2   =   is_singular( 'vendor' );

            $_condition_3   =   is_tax( 'venue-type' ) || is_tax( 'venue-location' );

            $_condition_4   =   is_page_template( 'user-template/search-venue.php' );

            /**
             *  I Declare as Globally
             *  ---------------------
             */
            if(  $_condition_1 || $_condition_2 || $_condition_3 || $_condition_4  ){

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

                /**
                 *  Script Object
                 *  -------------
                 */
                wp_localize_script(

                    /**
                     *  1. Slug
                     *  -------
                     */
                    esc_attr( sanitize_title( __CLASS__ ) ),

                    /**
                     *  2. Object Handler
                     *  -----------------
                     */
                    esc_attr(   'SDWEDDINGDIRECTORY_RATING_OBJ' ),

                    /**
                     *  1. Rating Style
                     *  ---------------
                     */
                    array(

                        'normalFill'    =>  sanitize_hex_color( sdweddingdirectory_option( 'normalFill' ) ),

                        'ratedFill'     =>  sanitize_hex_color( sdweddingdirectory_option( 'ratedFill' ) ),
                    )
                );
            }
        }

        /**
         *  Venue Singular : Review Average
         *  ---------------------------------
         */
        public static function sdweddingdirectory_review_average( $args = [] ){

            /**
             *  Is array ?
             *  ----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Merge Args
                 *  ----------
                 */
                $args       =       wp_parse_args( $args, [

                                        'post_id'               =>      absint( '0' ),

                                        'active_tab'            =>      false,

                                        'id'                    =>      sanitize_title( 'venue_ratings' ),

                                        'icon'                  =>      'fa fa-star-half-full',

                                        'heading'               =>      esc_attr__( 'Review', 'sdweddingdirectory-reviews' ),

                                        'card_info'             =>      true,

                                        'handler'               =>      ''

                                    ] );

                /**
                 *  Extract the data
                 *  ----------------
                 */
                extract( $args );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return;
                }

                /**
                 *  Layout 1
                 *  --------
                 */
                if( $layout == absint( '1' ) ){

                    /**
                     *  Count publish review
                     *  --------------------
                     *  Load Comments
                     *  -------------
                     */
                    $have_review    =      parent:: have_publish_review( [  'venue_id'  =>  absint( $post_id )  ] );

                    /**
                     *  Make sure rating is not empty!
                     *  ------------------------------
                     */
                    if( empty( $have_review ) ){

                        return;
                    }

                    $handler       .=      sprintf(    '<div class="card-shadow-body border-bottom">

                                                            <div class="row g-0">

                                                                <div class="col-md-auto">

                                                                    <div class="review-count">
                                                                    
                                                                        <span>%1$s</span>

                                                                        <small>%2$s</small>

                                                                        <div class="sdweddingdirectory_review stars" data-review="%1$s"></div>
                                                                
                                                                    </div>

                                                                </div>

                                                                <div class="col">

                                                                    <div class="row mt-3 mt-md-0">%3$s %4$s %5$s %6$s %7$s</div>

                                                                </div>

                                                            </div>

                                                        </div>',


                                                        /**
                                                         *  1. Get Average Rating
                                                         *  ---------------------
                                                         */
                                                        apply_filters( 'sdweddingdirectory/rating/average', absint( '0' ), [

                                                            'venue_id'        =>      absint( $post_id ),

                                                        ] ),

                                                        /**
                                                         *  2. Translation String
                                                         *  ---------------------
                                                         */
                                                        esc_attr__( 'out of 5.0', 'sdweddingdirectory-reviews' ),

                                                        /**
                                                         *  3. Quality Review
                                                         *  -----------------
                                                         */
                                                        parent:: venue_review_status( [

                                                            'icon'      =>      apply_filters( 'sdweddingdirectory/rating/icon/quality-of-service',

                                                                                    '<i class="fa fa-smile-o"></i>'
                                                                                ),

                                                            'rating'    =>      apply_filters( 'sdweddingdirectory/rating/average', '', [

                                                                                    'venue_id'        =>      absint( $post_id ),

                                                                                    'meta_key'          =>      sanitize_key( 'quality_service' )

                                                                                ] ),

                                                            'lable'     =>      esc_attr__( 'Quality of Service', 'sdweddingdirectory-reviews' )

                                                        ] ),

                                                        /**
                                                         *  4. Facilities Review
                                                         *  --------------------
                                                         */
                                                        parent:: venue_review_status( [

                                                            'icon'      =>      apply_filters( 'sdweddingdirectory/rating/icon/responsiveness',

                                                                                    '<i class="fa fa-exchange"></i>'
                                                                                ),

                                                            'rating'    =>      apply_filters( 'sdweddingdirectory/rating/average', '', [

                                                                                    'venue_id'        =>      absint( $post_id ),

                                                                                    'meta_key'          =>      sanitize_key( 'facilities' )

                                                                                ] ),

                                                            'lable'     =>      esc_attr__( 'Responsiveness', 'sdweddingdirectory-reviews' )

                                                        ] ),

                                                        /**
                                                         *  5. Staff Review
                                                         *  ---------------
                                                         */
                                                        parent:: venue_review_status( [

                                                            'icon'      =>      apply_filters( 'sdweddingdirectory/rating/icon/professionalism',

                                                                                    '<i class="fa fa-male"></i>'
                                                                                ),

                                                            'rating'    =>      apply_filters( 'sdweddingdirectory/rating/average', '', [

                                                                                    'venue_id'        =>      absint( $post_id ),

                                                                                    'meta_key'          =>      sanitize_key( 'staff' )

                                                                                ] ),

                                                            'lable'     =>      esc_attr__( 'Professionalism', 'sdweddingdirectory-reviews' )

                                                        ] ),

                                                        /**
                                                         *  6. Flexibility Review
                                                         *  ---------------------
                                                         */
                                                        parent:: venue_review_status( [

                                                            'icon'      =>      apply_filters( 'sdweddingdirectory/rating/icon/flexibility',

                                                                                    '<i class="fa fa-sliders"></i>'
                                                                                ),

                                                            'rating'    =>      apply_filters( 'sdweddingdirectory/rating/average', '', [

                                                                                    'venue_id'        =>      absint( $post_id ),

                                                                                    'meta_key'          =>      sanitize_key( 'flexibility' )

                                                                                ] ),

                                                            'lable'     =>      esc_attr__( 'Flexibility', 'sdweddingdirectory-reviews' )

                                                        ] ),

                                                        /**
                                                         *  7. Value of money Review
                                                         *  ------------------------
                                                         */
                                                        parent:: venue_review_status( [

                                                            'icon'      =>      apply_filters( 'sdweddingdirectory/rating/icon/value-for-money',

                                                                                    '<i class="fa fa-dollar"></i>'
                                                                                ),

                                                            'rating'    =>      apply_filters( 'sdweddingdirectory/rating/average', '', [

                                                                                    'venue_id'        =>      absint( $post_id ),

                                                                                    'meta_key'          =>      sanitize_key( 'value_of_money' )

                                                                                ] ),

                                                            'lable'     =>      esc_attr__( 'Value for Money', 'sdweddingdirectory-reviews' )

                                                        ] )
                                            );

                    /**
                     *  Have Reviews ?
                     *  --------------
                     */
                    if( parent:: _have_data( absint( $have_review ) ) ){

                        $handler        .= 
                        
                        sprintf(   '<div class="card-shadow-body d-md-flex justify-content-between align-items-center py-3">

                                        <strong>%1$s</strong>

                                    </div>

                                    <div class="card-shadow-body border-top">%2$s</div>',

                                    /**
                                     *  1. Start work here
                                     *  ------------------
                                     */
                                    sprintf( esc_attr__( '%1$s Reviews For %2$s', 'sdweddingdirectory-reviews' ),

                                        /**
                                         *  1. Get Counter For Current Venue Review
                                         *  -----------------------------------------
                                         */
                                        absint( $have_review ),

                                        /**
                                         *  2. Get The Venue Name
                                         *  -----------------------
                                         */
                                        esc_attr( get_the_title( absint( $post_id ) ) )
                                    ),

                                    /**
                                     *  2. Rating Data
                                     *  --------------
                                     */
                                    parent:: get_review_comment( [

                                        'venue_id'    =>      absint( $post_id ),

                                        'echo'          =>      false

                                    ] )
                        );
                    }

                    /**
                     *  Card Info Enable 
                     *  ----------------
                     */
                    if(  $card_info  ){

                        printf(     '<div class="card-shadow position-relative">

                                        <a id="section_%1$s" class="anchor-fake"></a>

                                        <div class="card-shadow-header d-md-flex justify-content-between align-items-center">

                                            <h3><i class="%2$s"></i> %3$s</h3> %4$s

                                        </div>

                                        %5$s

                                    </div>',

                                    /**
                                     *  1. Tab name
                                     *  -----------
                                     */
                                    sanitize_key( $id ),

                                    /**
                                     *  2. Tab Icon
                                     *  -----------
                                     */
                                    $icon,

                                    /**
                                     *  3. Heading 
                                     *  ----------
                                     */
                                    esc_attr( $heading ),

                                    /**
                                     *  4. Write A Review button — always visible
                                     *  ------------------------------------------
                                     */
                                    parent:: _have_review_permission()

                                    ?   sprintf(    '<a href="#section_write_your_review"
                                                    class="btn btn-sm text-white mt-3 mt-md-0"
                                                    style="background-color: var(--sdweddingdirectory-color-orange);"
                                                    id="write-review-button">%1$s</a>',
                                                    esc_attr__( 'Write a review', 'sdweddingdirectory-reviews' )
                                        )

                                    :   sprintf(    '<a class="btn btn-sm text-white mt-3 mt-md-0"
                                                    style="background-color: var(--sdweddingdirectory-color-orange);"
                                                    %1$s>%2$s</a>',
                                                    apply_filters( 'sdweddingdirectory/couple-login/attr', '' ),
                                                    esc_attr__( 'Write a review', 'sdweddingdirectory-reviews' )
                                        ),

                                    /**
                                     *  5. Output
                                     *  ---------
                                     */
                                    $handler
                        );
                    }

                    /**
                     *  Direct Output
                     *  -------------
                     */
                    else{

                        print   $handler;
                    }
                }

                /**
                 *  Layout 2
                 *  --------
                 */
                if( $layout == absint( '2' ) && parent:: _have_review_permission() ){

                    /**
                     *  Tab Overview
                     *  ------------
                     */
                    printf( '<a href="#section_%1$s" class="%2$s"><i class="%3$s"></i> %4$s</a>',

                            /**
                             *  Tab name
                             *  --------
                             */
                            !   empty( $have_review ) 

                            ?   esc_attr( $id )

                            :   esc_attr( 'write_your_review' ),

                            /**
                             *  Default Active
                             *  --------------
                             */
                            $active_tab     ?       sanitize_html_class( 'active' )     :   '',

                            /**
                             *  Tab Icon
                             *  --------
                             */
                            $icon,

                            /**
                             *  Tab Title
                             *  ---------
                             */
                            $heading
                    );
                }

                /**
                 *  Layout 3 - Tabing Style
                 *  -----------------------
                 */
                if( $layout == absint( '3' ) ){

                    ob_start();

                    /**
                     *  List of slider tab icon
                     *  -----------------------
                     */
                    call_user_func( [ __CLASS__, __FUNCTION__ ], [

                        'post_id'       =>      absint( $post_id ),

                        'layout'        =>      absint( '1' ),

                        'card_info'     =>      false

                    ] );

                    $data   =   ob_get_contents();

                    ob_end_clean();

                    /**
                     *  Tab layout
                     *  ----------
                     */
                    printf( '[sdweddingdirectory_tab icon="%1$s" title="%2$s"]%3$s[/sdweddingdirectory_tab]', 

                        /**
                         *  Tab Icon
                         *  --------
                         */
                        $icon,

                        /**
                         *  Tab Title
                         *  ---------
                         */
                        $heading,

                        /**
                         *  Card Body
                         *  ---------
                         */
                        $data
                    );
                }
            }
        }

        /**
         *   Venue Review - Single Page Form
         *   ---------------------------------
         */
        public static function sdweddingdirectory_submit_review( $args = [] ){

            /**
             *  Is array ?
             *  ----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Merge Args
                 *  ----------
                 */
                $args       =       wp_parse_args( $args, [

                                        'post_id'               =>      absint( '0' ),

                                        'active_tab'            =>      false,

                                        'id'                    =>      sanitize_title( __FUNCTION__ ),

                                        'icon'                  =>      'fa fa-pencil',

                                        'heading'               =>      esc_attr__( 'Write A Review', 'sdweddingdirectory-reviews' ),

                                        'card_info'             =>      true,

                                        'handler'               =>      ''

                                    ] );

                /**
                 *  Extract the data
                 *  ----------------
                 */
                extract( $args );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return;
                }

                /**
                 *  Make sure it's not admin && vendor
                 *  ----------------------------------
                 */
                if( parent:: _have_review_permission() ){

                    /**
                     *  Couple ID
                     *  ---------
                     */
                    $couple_id              =      parent:: post_id();

                    /**
                     *  Extract
                     *  -------
                     */
                    extract( [

                        'is_couple'         =>      parent:: is_couple(),

                        'couple_rating'     =>      parent:: couple_already_publish_review( array(

                                                        'venue_id'  =>  absint( $post_id ),

                                                        'couple_id'   =>  absint( $couple_id )

                                                    ) )
                    ] );

                    /**
                     *  Layout 1
                     *  --------
                     */
                    if( $layout == absint( '1' ) ){

                        /**
                         *  Body Content
                         *  ------------
                         */
                        $handler    .=      sprintf(  '<div class="card-shadow-body" id="sdweddingdirectory_review_comment_section">%1$s</div>',  

                                                        /**
                                                         *  Rating Form
                                                         *  -----------
                                                         */
                                                        parent:: rating_submit_form( [

                                                            'venue_id'        =>      absint( $post_id ),

                                                            'echo'              =>      false

                                                        ] )
                                            );

                        /**
                         *  Card Info Enable 
                         *  ----------------
                         */
                        if(  $card_info  ){

                            printf(     '<div class="card-shadow position-relative">

                                            <a id="section_%1$s" class="anchor-fake"></a>

                                            <div class="card-shadow-header d-md-flex justify-content-between align-items-center">

                                                <h3><i class="%2$s"></i> %3$s</h3>

                                            </div>

                                            %4$s

                                        </div>',

                                        /**
                                         *  1. Tab name
                                         *  -----------
                                         */
                                        sanitize_key( 'write_your_review' ),

                                        /**
                                         *  2. Tab Icon
                                         *  -----------
                                         */
                                        $icon,

                                        /**
                                         *  3. Heading 
                                         *  ----------
                                         */
                                        esc_attr( $heading ),

                                        /**
                                         *  4. Output
                                         *  ---------
                                         */
                                        $handler
                            );
                        }

                        /**
                         *  Direct Output
                         *  -------------
                         */
                        else{

                            print   $handler;
                        }
                    }

                    /**
                     *  Layout 2
                     *  --------
                     */
                    if( $layout == absint( '2' ) && false ){

                        /**
                         *  Tab Overview
                         *  ------------
                         */
                        printf( '<a href="#section_%1$s" class="%2$s"><i class="%3$s"></i> %4$s</a>',

                                /**
                                 *  Tab name
                                 *  --------
                                 */
                                esc_attr( $id ),

                                /**
                                 *  Default Active
                                 *  --------------
                                 */
                                $active_tab     ?       sanitize_html_class( 'active' )     :   '',

                                /**
                                 *  Tab Icon
                                 *  --------
                                 */
                                $icon,

                                /**
                                 *  Tab Title
                                 *  ---------
                                 */
                                $heading
                        );
                    }

                    /**
                     *  Layout 3 - Tabing Style
                     *  -----------------------
                     */
                    if( $layout == absint( '3' ) && false ){

                        ob_start();

                        /**
                         *  List of slider tab icon
                         *  -----------------------
                         */
                        call_user_func( [ __CLASS__, __FUNCTION__ ], [

                            'post_id'       =>      absint( $post_id ),

                            'layout'        =>      absint( '1' ),

                            'card_info'     =>      false

                        ] );

                        $data   =   ob_get_contents();

                        ob_end_clean();

                        /**
                         *  Tab layout
                         *  ----------
                         */
                        printf( '[sdweddingdirectory_tab icon="%1$s" title="%2$s"]%3$s[/sdweddingdirectory_tab]', 

                            /**
                             *  Tab Icon
                             *  --------
                             */
                            $icon,

                            /**
                             *  Tab Title
                             *  ---------
                             */
                            $heading,

                            /**
                             *  Card Body
                             *  ---------
                             */
                            $data
                        );
                    }
                }
            }
        }

        /**
         *  Venue Review Summary
         *  ----------------------
         */
        public static function venue_header_left_review( $args = [] ){

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

                    'post_id'       =>      absint( '0' ),

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return;
                }

                /**
                 *  Count the Rating
                 *  ----------------
                 */
                $count_rating       =       apply_filters( 'sdweddingdirectory/rating/found', '', [

                                                'venue_id'  =>  absint( $post_id )

                                            ] );

                /**
                 *  Make sure rating found
                 *  ----------------------
                 */
                if( empty( $count_rating ) ){

                    return;
                }

                /**
                 *  1. Venue Review Counter Showing After Venue Slider Section
                 *  --------------------------------------------------------------
                 */
                printf( '<li class="d-flex align-items-center"><i class="fa fa-star-half-full"></i> %1$s ( %2$s - %3$s )</li>',

                        /**
                         *  1. Get Average Rating
                         *  ---------------------
                         */
                        apply_filters( 'sdweddingdirectory/rating/average', '', [

                            'venue_id'  =>  absint( $post_id ),

                        ] ),

                        /**
                         *  2. Get Total Review
                         *  -------------------
                         */
                        absint( $count_rating ),

                        /**
                         *  3. Translation String
                         *  ---------------------
                         */
                        esc_attr__( 'Reviews', 'sdweddingdirectory-reviews' )
                );
            }
        }
    }

    /**
     *  SDWeddingDirectory - Review Object Load
     *  -------------------------------
     */
    SDWeddingDirectory_Singular_Venue_Reviews::get_instance();
}
