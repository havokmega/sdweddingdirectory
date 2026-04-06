<?php
/**
 *  SDWeddingDirectory - Venue Filter & Hooks
 *  -----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Singular_Header_Section' ) && class_exists( 'SDWeddingDirectory_Singular_Venue' ) ){

    /**
     *  SDWeddingDirectory - Venue Filter & Hooks
     *  -----------------------------------
     */
    class SDWeddingDirectory_Venue_Singular_Header_Section extends SDWeddingDirectory_Singular_Venue {

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
             *  Venue Header Section Left Content [ Title Section ]
             *  -----------------------------------------------------
             */
            add_action( 'sdweddingdirectory/venue/header/left/section', [ $this, 'venue_title' ], absint( '10' ), absint( '1' ) );

            /**
             *  Venue Header Section Left Content [ Location Section ]
             *  --------------------------------------------------------
             */
            add_action( 'sdweddingdirectory/venue/header/left/section', [ $this, 'venue_location' ], absint( '20' ), absint( '1' ) );

            /**
             *  Venue Header Section Left Content [ Venue Pricing Summary ]
             *  ---------------------------------------------------------------
             */
            add_action( 'sdweddingdirectory/venue/header/left/overview', [ $this, 'venue_pricing' ], absint( '20' ), absint( '1' ) );
            
            /**
             *  Venue Header Section Left Content [ Venue Have Seating Capacity ? ]
             *  -----------------------------------------------------------------------
             */
            add_action( 'sdweddingdirectory/venue/header/left/overview', [ $this, 'venue_guest' ], absint( '30' ), absint( '1' ) );

            /**
             *  Venue Header Section Left Content [ Venue Views ]
             *  -----------------------------------------------------
             */
            add_action( 'sdweddingdirectory/venue/header/left/overview', [ $this, 'venue_views' ], absint( '40' ), absint( '1' ) );

            /**
             *  Venue Singular page - Social Media Share
             *  ------------------------------------------
             */
            add_action( 'sdweddingdirectory/venue/header/right/overview', [ $this, 'venue_share' ], absint( '100' ), absint( '1' ) );
        }

        /**
         *  Venue Header Section Left Content [ Title Section ]
         *  -----------------------------------------------------
         */
        public static function venue_title( $args = [] ){

            /**
             *  Is array ?
             *  ----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract the data
                 *  ----------------
                 */
                extract( $args );

                /**
                 *  Venue Title
                 *  -------------
                 */
                if( get_the_title( $post_id ) !== '' ){

                    printf(     '<h1 class="heading">%1$s %2$s</h1>',

                        /**
                         *  Title
                         *  -----
                         */
                        esc_attr( get_the_title( $post_id ) ),

                        /**
                         *  2. Verify Badge
                         *  ---------------
                         */
                        apply_filters( 'sdweddingdirectory/venue/post/verify-badge', [

                            'post_id'       =>      absint( $post_id ),

                            'layout'        =>      absint( '2' )

                        ] )
                    );
                }
            }
        }

        /**
         *  Venue Header Section Left Content [ Location Section ]
         *  --------------------------------------------------------
         */
        public static function venue_location( $args = [] ){

            /**
             *  Is array ?
             *  ----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract the data
                 *  ----------------
                 */
                extract( $args );

                /**
                 *  Location Info
                 *  -------------
                 */
                print       apply_filters( 'sdweddingdirectory/post/location', [

                                'post_id'       =>      absint( $post_id ),

                                'taxonomy'      =>      esc_attr( 'venue-location' ),

                                'before'        =>      '<p class="mb-md-0 mb-lg-3 d-flex align-items-center">',

                                'after'         =>      '</p>'

                            ] );
            }
        }

        /**
         *  Venue Header Section Left Content [ Venue Pricing Summary ]
         *  ---------------------------------------------------------------
         */
        public static function venue_pricing( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){
                
                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( $args );

                $_min_price  =  get_post_meta( absint( $post_id ), sanitize_key( 'venue_min_price' ), true );

                $_max_price  =  get_post_meta( absint( $post_id ), sanitize_key( 'venue_max_price' ), true );

                $_condition_1   =   $_min_price > absint( '0' );

                $_condition_2   =   $_max_price > absint( '0' );


                if( $_condition_1 ){

                    printf(
                        '<li class="d-flex align-items-center"><i class="fa fa-money"></i>%1$s %2$s</li>',
                        esc_html__( 'Starting at', 'sdweddingdirectory-venue' ),
                        sdweddingdirectory_pricing_possition( absint( $_min_price ) )
                    );
                }
            }
        }

        /**
         *  Venue Header Section Left Content [ Venue Have Seating Capacity ? ]
         *  -----------------------------------------------------------------------
         */
        public static function venue_guest( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){
                
                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( $args );

                /**
                 *  Have Seat Data in Post ?
                 *  ------------------------
                 */
                $_post_have_seat_data   =   get_post_meta( absint( $post_id ), sanitize_key( 'venue_seat_capacity' ), true  );

                /**
                 *  Have Seat Capacity ?
                 *  --------------------
                 */
                $_have_seat_capacity    =   apply_filters( 'sdweddingdirectory/capacity-enable', [

                                                'term_id'       =>      apply_filters( 'sdweddingdirectory/post/term-parent', [

                                                                            'post_id'   =>  absint( $post_id ),

                                                                            'taxonomy'  =>  esc_attr( 'venue-type' )

                                                                        ] )
                                            ] );

                /**
                 *  1. Post Have Seat Capacity >= 0 ?
                 *  ---------------------------------
                 */
                $_condition_1   =   ( $_post_have_seat_data !== '' && $_post_have_seat_data !== absint( '0' )  );

                /**
                 *  2. This Category Have Seat Capacity ?
                 *  -------------------------------------
                 */
                $_condition_2   =   ( $_have_seat_capacity == true || $_have_seat_capacity == 'true' || $_have_seat_capacity == absint( '1' ) );

                /**
                 *  Show capacity if data exists (no category gate)
                 *  ------------------------------------------------
                 */
                if( $_condition_1 ){

                    printf( '<li class="d-flex align-items-center"><i class="fa fa-users"></i> %1$s %2$s</li>',

                        absint( $_post_have_seat_data ),

                        esc_attr__( 'Guests', 'sdweddingdirectory-venue' )
                    );
                }
            }
        }
        
        /**
         *  Venue Header Section Left Content [ Venue Views ]
         *  -----------------------------------------------------
         */
        public static function venue_views( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){
                
                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( $args );

                /**
                 *  1. Count Visit Venue Singular Page
                 *  ------------------------------------
                 */
                $_page_view     =   absint( get_post_meta(  absint( $post_id ), sanitize_key( 'venue_page_view' ), true  ) );

                /**
                 *  Have View More then 1 ?
                 *  -----------------------
                 */
                if( $_page_view >= absint( '1' ) ){

                    /**
                     *  Number of View
                     *  --------------
                     */
                    printf( '<li class="d-flex align-items-center"><i class="fa fa-eye"></i> %1$s %2$s</li>',

                        /**
                         *  1. Page View
                         *  ------------
                         */
                        apply_filters( 'sdweddingdirectory/number-to-views', absint( $_page_view ) ),

                        /**
                         *  2. Translation Ready String
                         *  ---------------------------
                         */
                        esc_attr__( 'View', 'sdweddingdirectory-venue' )
                    );
                }
            }
        }

        /**
         *  Venue Singular page - Social Media Share
         *  ------------------------------------------
         */
        public static function venue_share( $args = [] ){

            /**
             *  Have Data ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                extract( wp_parse_args( $args, array(

                    'post_id'   =>  absint( '0' )

                ) ) );

                /** 
                 *  Is Empty!
                 *  ---------
                 */
                if( empty( $post_id ) ){

                    return;
                }

                /**
                 *  Share Post
                 *  ----------
                 */
                printf( '<li class="d-flex align-items-center">

                            <a  href="javascript:"   

                                class="sdweddingdirectory-share-post-model d-flex align-items-center"

                                data-post-id="%1$s">

                                <i class="fa fa-share-alt"></i>

                                <span>%2$s</span>

                            </a>

                        </li>',

                        /**
                         *  1. Post ID
                         *  ----------
                         */
                        absint( $post_id ),

                        /**
                         *  2. Translation Ready String
                         *  ---------------------------
                         */
                        esc_attr__( 'Share', 'sdweddingdirectory-venue' )
                );
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Venue_Singular_Header_Section:: get_instance();
}