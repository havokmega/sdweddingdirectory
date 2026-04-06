<?php
/**
 *  Filter & Hooks
 *  --------------
 */
if( ! class_exists( 'SDWeddingDirectory_Rating_Post_Data' ) && class_exists( 'SDWeddingDirectory_Review_Filters' ) ){

    /**
     *  Filter & Hooks
     *  --------------
     */
    class SDWeddingDirectory_Rating_Post_Data extends SDWeddingDirectory_Review_Filters{

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
             *  1. Rating post load 
             *  -------------------
             */
            add_filter( 'sdweddingdirectory/rating/post-data', [ $this, 'rating_post' ], absint( '10' ), absint( '1' ) );

            /**
             *   Ratings Collections
             *   -------------------
             */
            add_filter( 'sdweddingdirectory/rating/post-reviews', [ $this, 'post_reviews' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Load Reviews
         *  ------------
         */
        public static function rating_post( $args = [] ){

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
                 *   Couple + Vendor + Venue POST ID
                 *   ---------------------------------
                 */
                $couple_id                      =         get_post_meta( $post_id, sanitize_key( 'couple_id' ), true );

                $vendor_id                      =         get_post_meta( $post_id, sanitize_key( 'vendor_id' ), true );

                $venue_id                     =         get_post_meta( $post_id, sanitize_key( 'venue_id' ), true );

                /**
                 *  Args
                 *  ----
                 */
                $rating_post_args               =         array_merge(

                    /**
                     *  Total - Reviews on Rating Post
                     *  ------------------------------
                     */
                    apply_filters( 'sdweddingdirectory/rating/post-reviews', [

                        'post_id'       =>          absint( $post_id )

                    ] ),

                    /**
                     *  Another Related - Rating Post Data
                     *  ----------------------------------
                     */
                    [

                    /**
                     *  Post IDs Collection
                     *  -------------------
                     */
                    'post_id'                       =>          absint( $post_id ),

                    'couple_id'                     =>          absint( $couple_id ),

                    'vendor_id'                     =>          absint( $vendor_id ),

                    'venue_id'                    =>          absint( $venue_id ),

                    'rating_id'                     =>          absint( $post_id ),

                    /**
                     *  Title + Comment
                     *  ---------------
                     */
                    'couple_comment'                 =>         get_post_meta( $post_id, sanitize_key( 'couple_comment' ), true ),

                    'vendor_comment'                 =>         get_post_meta( $post_id, sanitize_key( 'vendor_comment' ), true ),

                    /**
                     *  Write Review Date / Time
                     *  ------------------------
                     */
                    'couple_response_time'           =>         get_post_meta( absint(  $post_id ), sanitize_key( 'couple_comment_time' ), true ),

                    'vendor_response_time'           =>         get_post_meta( absint(  $post_id ), sanitize_key( 'vendor_comment_time' ), true ),

                    /**
                     *  Profile Image
                     *  -------------
                     */
                    'couple_profile_img'            =>          apply_filters( 'sdweddingdirectory/user-profile-image', [

                                                                    'post_id'       =>      absint( $couple_id )

                                                                ] ),

                    'vendor_profile_img'            =>          apply_filters( 'sdweddingdirectory/user-profile-image', [

                                                                    'post_id'       =>      absint( $vendor_id )

                                                                ] ),

                    'vendor_business_img'           =>          apply_filters( 'sdweddingdirectory/vendor-business-image', [

                                                                    'post_id'       =>      absint( $vendor_id )

                                                                ] ),
                    /**
                     *  Name
                     *  ----
                     */
                    'couple_name'                   =>          apply_filters( 'sdweddingdirectory/user/full-name', [

                                                                    'post_id'       =>      absint( $couple_id ),

                                                                ] ),

                    'vendor_name'                   =>          apply_filters( 'sdweddingdirectory/user/full-name', [

                                                                    'post_id'       =>      absint( $vendor_id ),

                                                                ] ),
                    /**
                     *  Wedding Date
                     *  ------------
                     */
                    'wedding_date'                  =>          get_post_meta( absint( $couple_id ), sanitize_key( 'wedding_date' ), true  )

                ] );


                /**
                 *  Return - Rating Object Data
                 *  ---------------------------
                 */
                return          $rating_post_args;
            }
        }

        /**
         *  Rating - Post Reviews
         *  ---------------------
         */
        public static function post_reviews( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    /**
                     *  Rating Post ID
                     *  --------------
                     */
                    'post_id'               =>      absint( '0' ),

                    /**
                     *  Post - Reviews
                     *  --------------
                     */
                    'quality_service'       =>      absint( '0' ),

                    'facilities'            =>      absint( '0' ),

                    'staff'                 =>      absint( '0' ),

                    'flexibility'           =>      absint( '0' ),

                    'value_of_money'        =>      absint( '0' ),

                    'average'               =>      absint( '0' ),

                    'handler'               =>      []

                ] ) );

                /**
                 *  Have Post ?
                 *  -----------
                 */
                if( empty( $post_id ) ){

                    return      [];
                }

                /**
                 *  Quality Rateing
                 *  ---------------
                 */
                $handler[ 'quality_service' ]       =       absint( get_post_meta( $post_id, sanitize_key( 'quality_service' ), true ) );

                /**
                 *  Facilities Rating
                 *  -----------------
                 */
                $handler[ 'facilities' ]            =       absint( get_post_meta( $post_id, sanitize_key( 'facilities' ), true ) );

                /**
                 *  Staff Rating
                 *  ------------
                 */
                $handler[ 'staff' ]                 =       absint( get_post_meta( $post_id, sanitize_key( 'staff' ), true ) );

                /**
                 *  Flexibility Rating
                 *  ------------------
                 */
                $handler[ 'flexibility' ]           =       absint( get_post_meta( $post_id, sanitize_key( 'flexibility' ), true ) );

                /**
                 *  Value of Money ?
                 *  ----------------
                 */
                $handler[ 'value_of_money' ]        =       absint( get_post_meta( $post_id, sanitize_key( 'value_of_money' ), true ) );

                /**
                 *  Avarage Rating [ Default Return ]
                 *  =================================
                 */
                $handler[ 'average' ]               =       number_format_i18n(

                                                                    /**
                                                                     *  1. Average Number
                                                                     *  -----------------
                                                                     */
                                                                    (   absint( $handler[ 'quality_service' ] )

                                                                        +   absint( $handler[ 'facilities' ] )

                                                                        +   absint( $handler[ 'staff' ] )

                                                                        +   absint( $handler[ 'flexibility' ] )

                                                                        +   absint( $handler[ 'value_of_money' ] )

                                                                    )   /   absint( '5' ),

                                                                    /**
                                                                     *  2. One Decimal Added
                                                                     *  ---------------
                                                                     */
                                                                    absint( '1' )
                                                            );

                /**
                 *  Return - Rating Post All Reviews Collection
                 *  -------------------------------------------
                 */
                return      $handler;
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Rating_Post_Data:: get_instance();
}