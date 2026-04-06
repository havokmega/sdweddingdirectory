<?php
/**
 *  SDWeddingDirectory Venue Reviews Database
 *  -----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Reviews_Database' ) && class_exists( 'SDWeddingDirectory_Config' ) ){

    /**
     *  SDWeddingDirectory Venue Reviews Database
     *  -----------------------------------
     */
    class SDWeddingDirectory_Reviews_Database extends SDWeddingDirectory_Config{

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
         *  Get Average Raring
         *  ------------------
         */
        public static function get_avarage_rating( $args = [] ){

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

                    'post_id'                   =>      absint( '0' ),

                    'quality_rating'            =>      absint( '0' ),

                    'facilities_rating'         =>      absint( '0' ),

                    'staff_rating'              =>      absint( '0' ),

                    'flexibility_rating'        =>      absint( '0' ),

                    'value_of_money_rating'     =>      absint( '0' ),

                    '__return'                  =>      esc_attr( 'average_rating' ),

                ] ) );

                /**
                 *  Have Post ?
                 *  -----------
                 */
                if( empty( $post_id ) ){

                    return      absint( '0' );
                }

                /**
                 *  Quality Rateing
                 *  ---------------
                 */
                $quality_rating         =   absint( get_post_meta( $post_id, sanitize_key( 'quality_service' ), true ) );

                /**
                 *  Facilities Rating
                 *  -----------------
                 */
                $facilities_rating      =   absint( get_post_meta( $post_id, sanitize_key( 'facilities' ), true ) );

                /**
                 *  Staff Rating
                 *  ------------
                 */
                $staff_raring           =   absint( get_post_meta( $post_id, sanitize_key( 'staff' ), true ) );

                /**
                 *  Flexibility Rating
                 *  ------------------
                 */
                $flexibility_rating     =   absint( get_post_meta( $post_id, sanitize_key( 'flexibility' ), true ) );

                /**
                 *  Value of Money ?
                 *  ----------------
                 */
                $value_of_money_rating  =   absint( get_post_meta( $post_id, sanitize_key( 'value_of_money' ), true ) );

                /**
                 *  Quality Rateing
                 *  ---------------
                 */
                if( $__return == esc_attr( 'quality_rating' ) ){

                    return      absint( $quality_rating );
                }

                /**
                 *  Facilities Rateing
                 *  ------------------
                 */
                elseif( $__return == esc_attr( 'facilities_rating' ) ){

                    return      absint( $facilities_rating );
                }

                /**
                 *  Staff Rateing
                 *  -------------
                 */
                elseif( $__return == esc_attr( 'staff_raring' ) ){

                    return      absint( $staff_raring );
                }

                /**
                 *  Flexibility Rating
                 *  ------------------
                 */
                elseif( $__return == esc_attr( 'flexibility_rating' ) ){

                    return      absint( $flexibility_rating );
                }

                /**
                 *  Value of Money ?
                 *  ----------------
                 */
                elseif( $__return == esc_attr( 'value_of_money_rating' ) ){

                    return      absint( $value_of_money_rating );
                }

                /**
                 *  Avarage Rating [ Default Return ]
                 *  =================================
                 */
                elseif( $__return == esc_attr( 'average_rating' ) ){

                    return      (       absint( $quality_rating ) 

                                    +   absint( $facilities_rating ) 

                                    +   absint( $staff_raring ) 

                                    +   absint( $flexibility_rating ) 

                                    +   absint( $value_of_money_rating )
                                )   

                                /   absint( '5' );
                }
            }
        }

        /**
         *  Find out request post for current login vendor
         *  ----------------------------------------------
         */
        public static function get_total_review_for_vendor(){

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

                'post_type'         =>  esc_attr( 'venue-review' ),

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
         *  Write Review Permission
         *  -----------------------
         */
        public static function _have_review_permission(){

            /**
             *  Check Write Review Permission
             *  -----------------------------
             */
            if( ( current_user_can( 'administrator' ) || parent:: is_vendor() ) ){

                return      false;
            }

            /**
             *  Is Couple
             *  ---------
             */
            else{

                return      true;
            }
        }

        /**
         *  Is Owner of Venue ( Vendor )
         *  ------------------------------
         */
        public static function _is_owner_of_venue( $_listign_id = '' ){

            global $current_user, $post, $wp_query;

            $_venue_id    =   ( isset( $_listign_id ) && $_listign_id !== '' ) 

                            ?   absint( $_listign_id ) 

                            :   absint( '0' );

            $user_roles     =   get_userdata( absint( get_post( absint( $_venue_id ) )->post_author ) )->roles;

            /**
             *   1. Is Venue Post Type ?
             *   -------------------------
             */
            $_security_1 = (  get_post_type( absint( $_venue_id ) ) == esc_attr( 'venue' ) );

            /**
             *  2. Venue Id to Get > Author ID  to get > Author "ROLE" is vendor ?
             *  --------------------------------------------------------------------
             */
            $_security_2    =  in_array( esc_attr( 'vendor' ), $user_roles, true );

            /**
             *  3. Is Vendor Login ?
             *  --------------------
             */
            $_security_3   =   parent:: is_vendor();

            /**
             *  Check Security & Permission
             *  ---------------------------
             */
            if( $_security_1 && $_security_2 && $_security_3 ){

                return true;

            }else{

                return false;
            }
        }

        /**
         *  Translation String : Couple already publish review
         *  --------------------------------------------------
         */
        public static function couple_already_publish_review_message(){

            return

            sprintf( '<p class="text-info">%1$s</p>',

                    /**
                     *  1. Review alreay publish but it's in pending mode
                     *  -------------------------------------------------
                     */
                    esc_attr__( 'Please wait for your review verify via admin.', 'sdweddingdirectory-reviews' )
            );
        }

        /**
         *  Translation String : Couple Review is live 
         *  -------------------------------------------
         */
        public static function couple_review_publish_message(){

            return 

            sprintf( '<p class="text-success">%1$s</p>', 

                /**
                 *  1. Get Couple Review is live ( publish )
                 *  ----------------------------------------
                 */
                esc_attr__( 'Your review status is publish.', 'sdweddingdirectory-reviews' )
            );
        }

        /**
         *  Write Review Input Fields
         *  -------------------------
         */
        public static function venue_review_input( $args = [] ){

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

                    'icon'          =>      apply_filters( 'sdweddingdirectory/rating/icon/quality-of-service',

                                                '<i class="fa fa-smile-o"></i>'
                                            ),

                    'id'            =>      esc_attr( parent:: _rand() ),

                    'lable'         =>      esc_attr__( 'Service', 'sdweddingdirectory-reviews' ),

                    'post_id'       =>      absint( '0' ),

                    'echo'          =>      false,

                    'handler'       =>      ''

                ] ) );

                /**
                 *  Handling
                 *  --------
                 */
                $handler    .=
 
                sprintf(   '<div class="col-md-4 col-6 mb-3 mb-md-0">

                                <div class="review-option text-start">
                                    
                                    <div class="icon">%1$s</div>

                                    <div class="count">

                                        <strong>%2$s</strong>

                                        <div class="rating-stars">
                                        
                                            <input autocomplete="off" id="%3$s" name="%3$s" type="hidden" value="%4$s" />

                                            <div class="couple_submit_rating"></div>

                                        </div>

                                    </div>

                                </div>

                            </div>',  

                            /**
                             *  1. Icon
                             *  -------
                             */
                            $icon,

                            /**
                             *  2. Label
                             *  --------
                             */
                            esc_attr( $lable ),

                            /**
                             *  3. ID
                             *  -----
                             */
                            sanitize_title( $id ),

                            /**
                             *  4. Rating ID exists ?
                             *  ---------------------
                             */
                            !   empty( $post_id )

                            ?   get_post_meta( $post_id, $id, true )

                            :   ''
                );
                
                /**
                 *  Print Data
                 *  ----------
                 */
                if( $echo ){

                    print       $handler;
                }

                /**
                 *  Return Data
                 *  -----------
                 */
                else{

                    return      $handler;
                }
            }
        }

        /**
         *  Review : Comments Summary
         *  -------------------------
         */
        public static function venue_review_status( $args = [] ){

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

                    'icon'          =>      '',

                    'rating'        =>      '',

                    'lable'         =>      '',

                    'print'         =>      false,

                    'handler'       =>      ''

                ] ) );

                /**
                 *  Filter
                 *  ------
                 */
                $rating     =   str_replace( ",", ".", $rating );

                $handler    =

                sprintf(    '<div class="col-md-4">

                                <div class="review-option">
                                    
                                    <div class="icon">%1$s <span class="review-each-count">%2$s</span></div>

                                    <div class="count">

                                        <strong>%3$s</strong>

                                        <div>
                                            <div class="bar-base">

                                                <div class="bar-filled" style="width: %4$s;">&nbsp;</div>

                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>', 

                            /**
                             *  1. Get Icon
                             *  -----------
                             */
                            $icon,

                            /**
                             *  2. Get Average Rating
                             *  ---------------------
                             */
                            $rating !== ''

                            ?   number_format( $rating, absint( '1' ) )

                            :   '',

                            /**
                             *  3. Label
                             *  --------
                             */
                            esc_attr( $lable ),

                            /**
                             *  4. Get Rating
                             *  -------------
                             */
                            $rating !== ''

                            ?   esc_attr( ( $rating * absint( '100' ) / absint( '5' ) ) . '%' )

                            :   ''
                );

                /**
                 *  Print
                 *  -----
                 */
                if( $print ){

                    print $handler;
                }

                /** 
                 *  Return Data
                 *  -----------
                 */
                else{

                    return $handler;
                }
            }
        }

        /**
         *  Couple Already Submited Review ?
         *  --------------------------------
         */
        public static function couple_already_publish_review( $args = [] ){

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

                    'venue_id'        =>      absint( '0' ),

                    'couple_id'         =>      absint( '0' ),

                    'handler'           =>      []

                ] ) );

                /**
                 *  Have POST ?
                 *  -----------
                 */
                $query      =   new WP_Query( array(

                                    'post_type'             =>  esc_attr( 'venue-review' ),

                                    'post_status'           =>  array( 'publish', 'pending' ),

                                    'posts_per_page'        =>  -1,

                                ) );

                /** 
                 *  Check Post Status
                 *  -----------------
                 */
                if ( $query->have_posts() ){

                    while ( $query->have_posts() ) { $query->the_post();

                        $found_venue_id       =       get_post_meta( get_the_ID(), sanitize_key( 'venue_id' ), true );

                        $found_couple_id        =       get_post_meta( get_the_ID(), sanitize_key( 'couple_id' ), true );

                        if( $found_venue_id == $venue_id && $found_couple_id == $couple_id ){

                            $handler[]       =       absint( get_the_ID() );
                        }
                    }

                    /**
                     *  Reset Filter
                     *  ------------
                     */
                    if ( isset( $query ) ) {
                        
                        wp_reset_postdata();
                    }
                }

                /**
                 *  Reset Filter
                 *  ------------
                 */
                if ( isset( $query ) ) {
                    
                    wp_reset_postdata();
                }
            }

            /**
             *  Return Collection
             *  -----------------
             */
            return          $handler;
        }

        /**
         *  Couple Already Submited Review ?
         *  --------------------------------
         */
        public static function couple_review_status( $args = [] ){

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

                    'meta_query'        =>      [],

                    'venue_id'        =>      absint( '0' ),

                    'couple_id'         =>      absint( '0' ),

                    'collection'        =>      ''

                ] ) );

                /**
                 *  Venue ID
                 *  ----------
                 */
                $meta_query[]       =   array(

                                            'key'           =>      esc_attr( 'venue_id' ),

                                            'type'          =>      esc_attr( 'numeric' ),

                                            'compare'       =>      esc_attr( '=' ),

                                            'value'         =>      absint( $venue_id ),
                                        );

                /**
                 *  Couple ID
                 *  ---------
                 */
                $meta_query[]       =   array(

                                            'key'           =>      esc_attr( 'couple_id' ),

                                            'type'          =>      esc_attr( 'numeric' ),

                                            'compare'       =>      esc_attr( '=' ),

                                            'value'         =>      absint( $couple_id ),
                                        );

                /**
                 *  Have POST ?
                 *  -----------
                 */
                $query          =       new WP_Query( array(

                                            'post_type'             =>  esc_attr( 'venue-review' ),

                                            'post_status'           =>  array( 'publish', 'pending' ),

                                            'posts_per_page'        =>  -1,

                                            'meta_query'            =>  array(

                                                    'relation'  =>  'AND',

                                                    $meta_query,
                                            ),

                                        ) );

                /** 
                 *  Check Post Status
                 *  -----------------
                 */
                if ( $query->have_posts() ){

                    while ( $query->have_posts() ) { $query->the_post();

                        $_post_status = esc_attr( get_post_status( absint( get_the_ID() ) ) );

                        /**
                         *  Post is Publish ?
                         *  -----------------
                         */
                        if( $_post_status == esc_attr( 'publish' ) ){

                            /**
                             *  Couple Review : Is live 
                             *  -----------------------
                             */
                            $collection         =   self:: couple_review_publish_message();
                        }

                        /**
                         *  Review in Draft ?
                         *  -----------------
                         */
                        else{

                            /**
                             *  Couple Review : Is Pending ( Admin Approval ) 
                             *  ---------------------------------------------
                             */
                            $collection         =   self:: couple_already_publish_review_message();
                        }
                    }

                    /**
                     *  Reset Filter
                     *  ------------
                     */
                    if ( isset( $query ) ) {
                        
                        wp_reset_postdata();
                    }
                }

                /**
                 *  Return Collection
                 *  -----------------
                 */
                return      $collection;
            }
        }

        /**
         *  Have Review ? How ? 
         *  -------------------
         */
        public static function get_venue_review_counter( $venue_id = ''){

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
             *  Have POST ?
             *  -----------
             */
            $args = array(

                'post_type'         => esc_attr( 'venue-review' ),

                'post_status'       =>  array( 'publish', 'pending' ),

                'posts_per_page'    =>  -1,

                'meta_query'        => array(

                        'relation'  => 'AND',

                        $meta_query,
                ),
            );

            $review_count_query =   new WP_Query( $args );

            return absint( $review_count_query->found_posts );

            if( isset( $review_count_query ) ){

                wp_reset_postdata();
            }
        }

        /**
         *  Have Review ? How ? 
         *  -------------------
         */
        public static function have_publish_review( $args = [] ){

            if( parent:: _is_array( $args ) ){

                extract( $args );

                global $wp_query, $post;

                $meta_query   = [];

                /**
                 *  Query have venue ID ?
                 *  -----------------------
                 */
                if( isset( $args['venue_id'] ) && $args['venue_id'] !== '' ){

                    /**
                     *  Venue ID
                     *  ----------
                     */
                    $meta_query[] = array(

                        'key'       =>  esc_attr( 'venue_id' ),
                        'type'      =>  esc_attr( 'numeric' ),
                        'compare'   =>  esc_attr( '=' ),
                        'value'     =>  absint( $venue_id ),
                    );
                }

                /**
                 *  Query have vendor ID ?
                 *  ----------------------
                 */
                if( isset( $args['vendor_id'] ) && $args['vendor_id'] !== '' ){

                    /**
                     *  Venue ID
                     *  ----------
                     */
                    $meta_query[] = array(

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
                $_have_publish_review_query = new WP_Query( 

                    array_merge( 

                        /**
                         *  1. First Args
                         *  -------------
                         */
                        array(

                            'post_type'         =>  esc_attr( 'venue-review' ),

                            'post_status'       =>  array( 'publish' ),

                            'posts_per_page'    =>  -1,

                        ),

                        /**
                         *  2. Have Meta Query ?
                         *  --------------------
                         */
                        ( parent:: _is_array( $meta_query ) )

                        ?   array(

                                'meta_query'        => array(

                                        'relation'  => 'AND',

                                        $meta_query,
                                )
                            )

                        :   []

                    )
                );

                /**
                 *  Return Number of review
                 *  -----------------------
                 */
                return absint( $_have_publish_review_query->found_posts );
            }
        }

        /**
         *  Get Average Rating
         *  ------------------
         */
        public static function _get_average_rating( $post_id = '' ){

            if( empty( $post_id ) ){

                return absint( '0' );
            }

            /**
             *  Rating
             *  ------
             */
            $_quality_rating        =   get_post_meta( $post_id, sanitize_key( 'quality_service' ), true );

            $_facilities_rating     =   get_post_meta( $post_id, sanitize_key( 'facilities' ), true );

            $_staff_rating          =   get_post_meta( $post_id, sanitize_key( 'staff' ), true );

            $_flexibility_rating    =   get_post_meta( $post_id, sanitize_key( 'flexibility' ), true );

            $_value_of_money_rating =   get_post_meta( $post_id, sanitize_key( 'value_of_money' ), true );            

            /**
             *  Get Average Review
             *  ------------------
             */
            return  number_format_i18n(

                        /**
                         *  1. Number
                         *  ---------
                         */
                        (       absint( $_quality_rating )

                            +   absint( $_facilities_rating ) 

                            +   absint( $_staff_rating )

                            +   absint( $_flexibility_rating )

                            +   absint( $_value_of_money_rating )

                        )   /   absint( '5' ),

                        /**
                         *  2. Float Point
                         *  --------------
                         */
                        absint( '1' )
                    );
        }


        /**
         *   Review Toggle Show
         *   ------------------
         */
        public static function show_review_toggle( $post_id = '' ){

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
            $_couple_id         =   get_post_meta( $post_id, sanitize_key( 'couple_id' ), true );

            /**
             *  Couple - Have Wedding Date ?
             *  ----------------------------
             */
            $_wedding_date      =   get_post_meta(

                                        /**
                                         *  1. Couple Post ID
                                         *  -----------------
                                         */
                                        absint( $_couple_id ),

                                        /**
                                         *  2. Meta Key
                                         *  -----------
                                         */
                                        sanitize_key( 'wedding_date' ), 

                                        true  
                                    );
            /**
             *  Name
             *  ----
             */
            $_couple_name   =   sprintf( '%1$s %2$s',

                                    get_post_meta( absint( $_couple_id ), sanitize_key( 'first_name' ), true ),

                                    get_post_meta( absint( $_couple_id ), sanitize_key( 'last_name' ), true )
                                );

            /**
             *  Show Review Toggle Bar
             *  ----------------------
             */
            return  

            sprintf( '  <div class="heading-wrap g-0">

                            <div class="heading">

                                <div class="col pl-0">

                                    <h4 class="mb-0">%1$s</h4>

                                    <div class="review-option-btn">

                                       <a data-bs-toggle="collapse" href="#%2$s" role="button" aria-expanded="false" class="collapsed">
                                        
                                            <span class="stars sdweddingdirectory_review" data-review="%3$s"></span>

                                            <span>%3$s <i class="fa fa-angle-down arrow"></i></span>

                                       </a>

                                    </div>

                                </div>
                             
                                <div class="col-auto">

                                    <small>%4$s</small>

                                </div>

                            </div>

                            <div id="%2$s" class="collapse">

                                <div class="row">

                                    %5$s <!-- Quality Review -->

                                    %6$s <!-- Facilities Review -->

                                    %7$s <!-- Staff Review -->

                                    %8$s <!-- Flexibility Review -->

                                    %9$s <!-- Value of money Review -->

                                </div>

                            </div>

                        </div>',

                        /**
                         *  1. Show Title
                         *  -------------
                         */
                        esc_attr( $_couple_name ),

                        /**
                         *  2. Review Show Toggle ID ( Toggle Purpose Only )
                         *  ------------------------------------------------
                         */
                        sprintf( '%1$s-%2$s-%3$s',

                            /**
                             *  1. site info
                             *  ------------
                             */
                            esc_attr( sanitize_title( get_bloginfo( 'name' ) ) ),

                            /**
                             *  2. Get Review ID
                             *  ----------------
                             */
                            esc_attr( 'review-id' ),

                            /**
                             *  3. Review ID
                             *  ------------
                             */
                            $post_id
                        ),

                        /**
                         *  3. Get Average Review
                         *  ---------------------
                         */
                        self:: _get_average_rating( $post_id ),

                        /**
                         *  4. Mariage Date
                         *  ---------------
                         */
                        ! empty( $_wedding_date )

                        ?   sprintf(  esc_attr__( 'Married on %1$s', 'sdweddingdirectory-reviews' ), 

                                /**
                                 *  1. Couple Wedding Date Get Through Couple Post ID
                                 *  -------------------------------------------------
                                 */
                                apply_filters( 'sdweddingdirectory/date-format', [

                                    'date'      =>      esc_attr( $_wedding_date )

                                ] )
                            )

                        :   '',

                        /**
                         *  5. Quality Review
                         *  -----------------
                         */
                        self:: venue_review_status( array(

                            'icon'      =>  apply_filters( 'sdweddingdirectory/rating/icon/quality-of-service',

                                                '<i class="fa fa-smile-o"></i>'
                                            ),

                            'rating'    =>  get_post_meta( absint(  $post_id ), sanitize_key( 'quality_service' ), true ),

                            'lable'     =>  esc_attr__( 'Quality of Service', 'sdweddingdirectory-reviews' )

                        ) ),

                        /**
                         *  6. Facilities Review
                         *  --------------------
                         */
                        self:: venue_review_status( array(                                   

                            'icon'      =>  apply_filters( 'sdweddingdirectory/rating/icon/responsiveness',

                                                '<i class="fa fa-exchange"></i>'
                                            ),

                            'rating'    =>  get_post_meta( absint(  $post_id ), sanitize_key( 'facilities' ), true ),

                            'lable'     =>  esc_attr__( 'Responsiveness', 'sdweddingdirectory-reviews' )

                        ) ),

                        /**
                         *  7. Staff Review
                         *  ---------------
                         */
                        self:: venue_review_status( array(

                            'icon'      =>  apply_filters( 'sdweddingdirectory/rating/icon/professionalism',

                                                '<i class="fa fa-male"></i>'
                                            ),

                            'rating'    =>  get_post_meta( absint(  $post_id ), sanitize_key( 'staff' ), true ),

                            'lable'     =>  esc_attr__( 'Professionalism', 'sdweddingdirectory-reviews' )

                        ) ),

                        /**
                         *  8. Flexibility Review
                         *  ---------------------
                         */
                        self:: venue_review_status( array(

                            'icon'      =>  apply_filters( 'sdweddingdirectory/rating/icon/flexibility',

                                                '<i class="fa fa-sliders"></i>'
                                            ),

                            'rating'    =>  get_post_meta( absint(  $post_id ), sanitize_key( 'flexibility' ), true ),

                            'lable'     =>  esc_attr__( 'Flexibility', 'sdweddingdirectory-reviews' )

                        ) ),

                        /**
                         *  9. Value of money Review
                         *  ------------------------
                         */
                        self:: venue_review_status( array(

                            'icon'      =>  apply_filters( 'sdweddingdirectory/rating/icon/value-for-money',

                                                '<i class="fa fa-dollar"></i>'
                                            ),

                            'rating'    =>  get_post_meta( absint(  $post_id ), sanitize_key( 'value_of_money' ), true ),

                            'lable'     =>  esc_attr__( 'Value for Money', 'sdweddingdirectory-reviews' )

                        ) )
            );
        }

        /**
         *  Review Thumb Alt
         *  ----------------
         */
        public static function review_thumb_alt( $post_id = '' ){

            if( get_post_type( $post_id ) == esc_attr( 'couple' ) ){

                $media_id   =   get_post_meta( $post_id, sanitize_key( 'user_image' ), true );
            }

            elseif( get_post_type( $post_id ) == esc_attr( 'vendor' ) ){

                $media_id   =   get_post_meta( $post_id, sanitize_key( 'business_icon' ), true );
            }

            else{

                $media_id   =   absint( '0' );
            }

            /**
             *  Return : Image Alt
             *  ------------------
             */
            return  esc_attr( parent:: _alt( array(

                        'media_id'  =>  absint( $media_id ),

                        'post_id'   =>  $post_id,

                        'start_alt' =>  esc_attr__( 'Venue Review', 'sdweddingdirectory-reviews' )

                    ) ) );
        }

        /**
         *  Collection For Reviews Summary
         *  ------------------------------
         */
        public static function get_venue_review( $args = [] ){

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

                    'venue_id'               =>      absint( '0' ),

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( empty( $venue_id ) ){

                    return;
                }

                /**
                 *  Return [ Rating Tab ] Overview
                 *  ------------------------------
                 */
                return  

                sprintf(    '<div class="card-shadow-body">

                                <div class="row g-0">

                                    <div class="col-md-auto">

                                        <div class="review-count"><!-- Average Rating --> %1$s <!-- / Average Rating --></div>

                                    </div>

                                    <div class="col">

                                        <div class="row">

                                            <!-- Quality of Service Rating --> %2$s <!-- / Quality of Service Rating -->

                                            <!-- Facilities Review --> %3$s  <!-- /  Facilities Review -->

                                            <!-- Staff Review -->  %4$s <!-- /  Staff Review -->

                                            <!-- Flexibility Review -->  %5$s <!-- /  Flexibility Review -->

                                            <!-- Value of money Review -->  %6$s <!-- /  Value of money Review -->

                                        </div>

                                    </div>

                                </div>

                            </div>',

                            /**
                             *  1. Venue Review Overview
                             *  --------------------------
                             */
                            sprintf( '<span>%1$s</span>

                                      <small>%2$s</small>

                                      <div class="sdweddingdirectory_review stars" data-review="%1$s"></div>',

                                    /**
                                     *  1. Get Average Rating
                                     *  ---------------------
                                     */
                                    apply_filters( 'sdweddingdirectory/rating/average', '', [

                                        'venue_id'    =>      absint( $venue_id )

                                    ] ),

                                    /**
                                     *  2. Translation String
                                     *  ---------------------
                                     */
                                    esc_attr__( 'out of 5.0', 'sdweddingdirectory-reviews' )
                            ),

                            /**
                             *  2. Quality Review
                             *  -----------------
                             */
                            self:: venue_review_status( [

                                'icon'          =>      apply_filters( 'sdweddingdirectory/rating/icon/quality-of-service',

                                                            '<i class="fa fa-smile-o"></i>'
                                                        ),

                                'rating'        =>      apply_filters( 'sdweddingdirectory/rating/average', '', [

                                                            'venue_id'  =>  absint( $venue_id ),

                                                            'meta_key'    =>  sanitize_key( 'quality_service' )

                                                        ] ),

                                'lable'         =>      esc_attr__( 'Quality of Service', 'sdweddingdirectory-reviews' )

                            ] ),

                            /**
                             *  3. Facilities Review
                             *  --------------------
                             */
                            self:: venue_review_status( [

                                'icon'          =>      apply_filters( 'sdweddingdirectory/rating/icon/responsiveness',

                                                            '<i class="fa fa-exchange"></i>'
                                                        ),

                                'rating'        =>      apply_filters( 'sdweddingdirectory/rating/average', '', [

                                                            'venue_id'  =>  absint( $venue_id ),

                                                            'meta_key'    =>  sanitize_key( 'facilities' )

                                                        ] ),

                                'lable'         =>      esc_attr__( 'Responsiveness', 'sdweddingdirectory-reviews' )

                            ] ),

                            /**
                             *  4. Staff Review
                             *  ---------------
                             */
                            self:: venue_review_status( [

                                'icon'          =>      apply_filters( 'sdweddingdirectory/rating/icon/professionalism',

                                                            '<i class="fa fa-male"></i>'
                                                        ),

                                'rating'        =>      apply_filters( 'sdweddingdirectory/rating/average', '', [

                                                            'venue_id'  =>  absint( $venue_id ),

                                                            'meta_key'    =>  sanitize_key( 'staff' )

                                                        ] ),

                                'lable'         =>      esc_attr__( 'Professionalism', 'sdweddingdirectory-reviews' )

                            ] ),

                            /**
                             *  Flexibility Review
                             *  ------------------
                             */
                            self:: venue_review_status( [

                                'icon'          =>      apply_filters( 'sdweddingdirectory/rating/icon/flexibility',

                                                            '<i class="fa fa-sliders"></i>'
                                                        ),

                                'rating'        =>      apply_filters( 'sdweddingdirectory/rating/average', '', [

                                                            'venue_id'  =>  absint( $venue_id ),

                                                            'meta_key'    =>  sanitize_key( 'flexibility' )

                                                        ] ),

                                'lable'         =>      esc_attr__( 'Flexibility', 'sdweddingdirectory-reviews' )

                            ] ),

                            /**
                             *  Value of money Review
                             *  ---------------------
                             */
                            self:: venue_review_status( [

                                'icon'          =>      apply_filters( 'sdweddingdirectory/rating/icon/value-for-money',

                                                            '<i class="fa fa-dollar"></i>'
                                                        ),

                                'rating'        =>      apply_filters( 'sdweddingdirectory/rating/average', '', [

                                                            'venue_id'  =>  absint( $venue_id ),

                                                            'meta_key'    =>  sanitize_key( 'value_of_money' )

                                                        ] ),

                                'lable'         =>      esc_attr__( 'Value for Money', 'sdweddingdirectory-reviews' )

                            ] )
                );
            }
        }

        /**
         *  Get Rating Tab Data
         *  -------------------
         */
        public static function get_venue_review_tab_data( $args = [] ){

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

                    'handler'               =>      [],

                    'meta_query'            =>      [],

                    'post_id'               =>      absint( '0' ),

                    'venue_id'            =>      absint( '0' ),

                    'couple_id'             =>      absint( '0' ),

                    'vendor_id'             =>      absint( '0' ),

                    'active_tab'            =>      absint( '0' )

                ] ) );

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( [

                    /**
                     *  Args
                     *  ----
                     */
                    'rating_obj'            =>      apply_filters( 'sdweddingdirectory/rating/post-data', [

                                                        'post_id'       =>      $post_id

                                                    ] ),

                    'unique_id'             =>      esc_attr( parent:: _rand()  ),

                    'venue_img'           =>      apply_filters( 'sdweddingdirectory/venue/post/thumbnail', [ 

                                                        'post_id'           =>      absint( $venue_id ),

                                                        'image_size'        =>      esc_attr( 'thumbnail' )

                                                    ] ),

                    'venue_name'          =>      esc_attr( get_the_title(  $venue_id  ) ),

                    'venue_location'      =>      apply_filters( 'sdweddingdirectory/post/location', [

                                                        'post_id'       =>      absint( $venue_id ),

                                                    ] ),

                    'randome_value'         =>      sanitize_title( get_bloginfo( 'name' ) ) . '-' .  absint( $post_id ),

                ] );

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( $rating_obj );

                /**
                 *  Tabs - Collection
                 *  -----------------
                 */
                $handler[ 'tabs' ][]    =

                sprintf( 

                    '<a class="nav-link %5$s" id="%6$s-tab"

                        href="#%6$s" aria-controls="%6$s" aria-selected="%7$s"

                        data-bs-toggle="pill"  role="tab">

                        <div class="reviews-media">

                            <div class="media">

                                <img class="thumb" src="%1$s" alt="%8$s" />

                                <div class="media-body">

                                    <div class="heading-wrap g-0">

                                        <div class="heading">

                                            <h4 class="mb-0">%2$s</h4>

                                            <div class="review-option-btn">

                                                <span class="stars sdweddingdirectory_review" data-review="%3$s"></span>

                                                <span>%3$s</span>
                                            
                                            </div>

                                            <small>%4$s</small>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </a>',

                    /**
                     *  1. Venue Image
                     *  ----------------
                     */
                    esc_url( $venue_img ),

                    /**
                     *  2. Venue Name
                     *  ---------------
                     */
                    esc_attr( $venue_name ),

                    /**
                     *  3. Get Average Rating
                     *  ---------------------
                     */
                    esc_attr( $average ),

                    /**
                     *  4. Venue Location
                     *  -------------------
                     */
                    $venue_location,

                    /**
                     *  5. Active Tab
                     *  -------------
                     */
                    $active_tab   ?   sanitize_html_class( 'active' )  :  '',

                    /**
                     *  6. Get Unique ID
                     *  ----------------
                     */
                    esc_attr( $unique_id ),

                    /**
                     *  7. Is Active ?
                     *  --------------
                     */
                    $active_tab

                    ?   sanitize_html_class( 'true' )

                    :   sanitize_html_class( 'false' ),

                   /**
                    *  8. Image Alt
                    *  ------------
                    */
                    esc_attr( self:: review_thumb_alt( absint( $couple_id ) ) )
                );

                /**
                 *  Tabs - Collection
                 *  -----------------
                 */
                $handler[ 'tab_content' ][]    =

                sprintf(    '<div class="tab-pane fade %1$s" id="%2$s" role="tabpanel" aria-labelledby="%2$s-tab">

                                <div class="border-bottom p-4">

                                    <div class="d-flex justify-content-between align-items-center">

                                        <h3 class="mb-0 fw-bold">%3$s</h3>

                                        <a href="%4$s" target="_blank" class="btn btn-default">

                                            <i class="fa fa-external-link me-2" aria-hidden="true"></i>

                                            <span>%5$s</span>

                                        </a>

                                    </div>

                                </div>

                                <div class="reviews-media">

                                    <div class="media">

                                        <div class="media-body">%6$s</div>

                                    </div>

                                </div>

                            </div>',

                            /**
                             *  1. Tab Content Active
                             *  ---------------------
                             */
                            $active_tab  ?   esc_attr( 'show active' )  :   '',

                            /**
                             *  2. Get Unique ID
                             *  ----------------
                             */
                            esc_attr( $unique_id ),

                            /**
                             *  3. Venue Title
                             *  ----------------
                             */
                            esc_attr( get_the_title( $venue_id ) ),

                            /**
                             *  4. Venue Link
                             *  ---------------
                             */
                            esc_url( get_the_permalink( $venue_id ) ),

                            /**
                             *  5. Translation Ready String
                             *  ----------------------------
                             */
                            esc_attr__( 'Preview', 'sdweddingdirectory-reviews' ),

                            /**
                             *  6. Rating Form
                             *  --------------
                             */
                            self:: rating_submit_form( [

                                'venue_id'        =>      absint( $venue_id ),

                                'echo'              =>      false

                            ] )
                );

                /**
                 *  Return Handler
                 *  --------------
                 */
                return          $handler;
            }
        }

        /**
         *  Get Rating Tab Data
         *  -------------------
         */
        public static function get_review_tab_data( $args = [] ){

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

                    'handler'               =>      [],

                    'meta_query'            =>      [],

                    'venue_id'            =>      absint( '0' ),

                    'counter'               =>      absint( '0' )

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
                 *  Have POST ?
                 *  -----------
                 */
                $query      =       new WP_Query( array(

                                        'post_type'         =>  esc_attr( 'venue-review' ),

                                        'post_status'       =>  array( 'publish' ),

                                        'meta_query'        =>  array(

                                                                    'relation'  => 'AND',

                                                                    $meta_query,
                                                                ),
                                    ) );

                /**
                 *  Have Venue Review ?
                 *  ---------------------
                 */
                if ( $query->have_posts() ){

                    /**
                     *  Load One By One
                     *  ---------------
                     */
                    while ( $query->have_posts() ) {    $query->the_post();

                        /**
                         *  Post ID
                         *  -------
                         */
                        $post_id       =   absint( get_the_ID() );

                        /**
                         *  Extract Args
                         *  ------------
                         */
                        extract( [

                            /**
                             *  Args
                             *  ----
                             */
                            'rating_obj'        =>      apply_filters( 'sdweddingdirectory/rating/post-data', [

                                                            'post_id'       =>      $post_id

                                                        ] ),

                            'unique_id'         =>      esc_attr( parent:: _rand()  ),

                        ] );

                        /**
                         *  Extract Args
                         *  ------------
                         */
                        extract( $rating_obj );

                        /**
                         *  Collection of Tabs
                         *  ------------------
                         */
                        $handler[ 'tab' ][]      =

                        sprintf( 

                            '<a class="nav-link %5$s" id="%6$s-tab"

                                href="#%6$s" aria-controls="%6$s" aria-selected="%7$s"

                                data-bs-toggle="pill"  role="tab">

                                <div class="reviews-media">

                                    <div class="media">

                                        <img class="thumb" src="%1$s" alt="%8$s" />

                                        <div class="media-body">

                                            <div class="heading-wrap g-0">

                                                <div class="heading">

                                                    <h4 class="mb-0">%2$s</h4>

                                                    <div class="review-option-btn">

                                                        <span class="stars sdweddingdirectory_review" data-review="%3$s"></span>

                                                        <span>%3$s</span>
                                                    
                                                    </div>

                                                    <small>%4$s</small>

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
                            esc_url( $couple_profile_img ),

                            /**
                             *  2. Couple Name
                             *  --------------
                             */
                            esc_attr( $couple_name ),

                            /**
                             *  3. Get Average Rating
                             *  ---------------------
                             */
                            esc_attr( $average ),

                            /**
                             *  4. Mariage Date
                             *  ---------------
                             */
                            ! empty( $wedding_date )

                            ?   sprintf( esc_attr__( 'Married on %1$s', 'sdweddingdirectory-reviews' ), 

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
                             *  5. Active Tab
                             *  -------------
                             */
                            $counter == absint( '0' )   ?   sanitize_html_class( 'active' )  :  '',

                            /**
                             *  6. Get Unique ID
                             *  ----------------
                             */
                            esc_attr( $unique_id ),

                            /**
                             *  7. Is Active ?
                             *  --------------
                             */
                            $counter == absint( '0' )

                            ?   sanitize_html_class( 'true' )

                            :   sanitize_html_class( 'false' ),

                           /**
                            *  8. Image Alt
                            *  ------------
                            */
                            esc_attr( self:: review_thumb_alt( absint( $couple_id ) ) )
                        );

                        /**
                         *  Collection of Tab Content
                         *  -------------------------
                         */
                        $handler[ 'tab_content' ][]     =   

                        sprintf(    '<div class="tab-pane fade %1$s" id="%2$s" role="tabpanel" aria-labelledby="%2$s-tab">

                                        <div class="reviews-media">

                                            <div class="media">

                                                <div class="media-body">

                                                    %7$s <!-- Show Review -->

                                                    <h3 class="fw-7">%3$s</h3>

                                                    <p>%4$s</p>

                                                    <div id="vendor_comment_area_%6$s">%5$s</div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>',

                                    /**
                                     *  1. Tab Content Active
                                     *  ---------------------
                                     */
                                    $counter == absint( '0' )  ?   esc_attr( 'show active' )  :   '',

                                    /**
                                     *  2. Get Unique ID
                                     *  ----------------
                                     */
                                    esc_attr( $unique_id ),

                                    /**
                                     *  3. Get The Review Title
                                     *  -----------------------
                                     */
                                    esc_attr( get_the_title() ),

                                    /**
                                     *  4. Get The Review Description
                                     *  -----------------------------
                                     */
                                    esc_attr( $couple_comment ),

                                    /**
                                     *  5. Check Vendor Already Response ?
                                     *  ----------------------------------
                                     */
                                    isset( $vendor_comment ) && parent:: _have_data( $vendor_comment )

                                    ?   sprintf(   '<div class="media reply-box">

                                                        <img src="%1$s" alt="%5$s" class="thumb">

                                                        <div class="media-body">

                                                            <div class="d-md-flex justify-content-between mb-3">

                                                                <h4 class="mb-0">%2$s</h4>

                                                                <small class="txt-blue">%4$s</small>

                                                            </div>

                                                            %3$s

                                                        </div>

                                                    </div>',

                                                    /**
                                                     *  1. Vendor Media
                                                     *  ---------------
                                                     */
                                                    esc_url( $vendor_business_img ),

                                                    /**
                                                     *  2. Vendor Name
                                                     *  --------------
                                                     */
                                                    esc_attr( $vendor_name ),

                                                    /**
                                                     *  3. Vendor Comment
                                                     *  -----------------
                                                     */
                                                    esc_attr( $vendor_comment ),

                                                    /**
                                                     *  4. Vendor Response Time
                                                     *  -----------------------
                                                     */
                                                    apply_filters( 'sdweddingdirectory/date-format', [

                                                        'date'      =>      esc_attr( $vendor_response_time )

                                                    ] ),

                                                   /**
                                                    *  5. Image Alt
                                                    *  ------------
                                                    */
                                                    esc_attr( self:: review_thumb_alt( absint( $vendor_id ) ) )
                                        )

                                    :   sprintf(   '<form class="sdweddingdirectory_vendor_response" id="%2$s" method="method"> 

                                                        <div class="mb-3">

                                                            <textarea class="%1$s form-control" 

                                                                id="%1$s_%2$s" name="%1$s" rows="6" 

                                                                placeholder="%3$s" required></textarea>

                                                        </div>

                                                        <input autocomplete="off" type="hidden" class="security" id="security_%2$s" value="%5$s" />

                                                        <input autocomplete="off" type="hidden" class="review_post_id" id="review_post_id_%2$s" value="%6$s" />

                                                        <button type="submit" class="btn btn-default loader" id="submit-%2$s">%4$s</button>

                                                    </form>',

                                                    /**
                                                     *  1. Textarea Name
                                                     *  ----------------
                                                     */
                                                    esc_attr( 'vendor_comment' ),

                                                    /**
                                                     *  2. Random ID
                                                     *  ------------
                                                     */
                                                    esc_attr( parent:: _rand() ),

                                                    /**
                                                     *  3. Placeholder
                                                     *  --------------
                                                     */
                                                    esc_attr__( 'Write Your Response', 'sdweddingdirectory-reviews' ),

                                                    /**
                                                     *  4. Button Text
                                                     *  --------------
                                                     */
                                                    esc_attr__( 'Add Your Reply', 'sdweddingdirectory-reviews' ),

                                                    /**
                                                     *  5. Security
                                                     *  -----------
                                                     */
                                                    wp_create_nonce( 'vendor_comment-' . absint( $post_id ) ),

                                                    /**
                                                     *  6. Review Post ID
                                                     *  -----------------
                                                     */
                                                    absint( $post_id )
                                        ),

                                    /**
                                     *  6. Get the Review Post ID
                                     *  -------------------------
                                     */
                                    absint( $post_id ),

                                    /**
                                     *  7. Get Average Rating
                                     *  ---------------------
                                     */
                                    self:: show_review_toggle( $post_id )
                        );

                        /**
                         *  Tab and Tab Content
                         *  -------------------
                         */
                        $counter++;
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
                 *  Return Handler
                 *  --------------
                 */
                return          $handler;
            }
        }

        /**
         *  Get Vendor Company IMG
         *  ----------------------
         */
        public static function _get_vendor_company_image( $post_id = '' ){

            $media_id   =   get_post_meta( $post_id, sanitize_key( 'business_icon' ), true );

            /**
             *  Return Media
             *  ------------
             */
            return  parent:: _have_data( $media_id )

                    ?   apply_filters( 'sdweddingdirectory/media-data', [

                            'media_id'         =>  absint( $media_id ),

                            'image_size'       =>  esc_attr( 'thumbnail' ),
                        ] )

                    :   esc_url( parent:: placeholder( 'vendor-brand-image' ) );
        }

        /**
         *  Venue Reviews Comment
         *  -----------------------
         */
        public static function get_review_comment( $args = [] ){

            /**
             *  Have Query ?
             *  ------------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( wp_parse_args( $args, [

                    'venue_id'        =>      absint( '0' ),

                    'vendor_id'         =>      absint( '0' ),

                    'handler'           =>      '',

                    'echo'              =>      true,

                    'meta_query'        =>      [],

                    'unique_id'         =>      parent:: _rand()

                ] ) );

                /**
                 *  Have Venue ID ?
                 *  -----------------
                 */
                if( ! empty( $venue_id ) ){

                    $meta_query[]       =       array(

                                                    'key'           =>      esc_attr( 'venue_id' ),

                                                    'type'          =>      esc_attr( 'numeric' ),

                                                    'compare'       =>      esc_attr( '=' ),

                                                    'value'         =>      absint( $venue_id ),
                                                );
                }

                /**
                 *  Have Vendor ID ?
                 *  ----------------
                 */
                if(  ! empty( $vendor_id ) ){

                    $meta_query[]       =       array(

                                                    'key'           =>      esc_attr( 'vendor_id' ),

                                                    'type'          =>      esc_attr( 'numeric' ),

                                                    'compare'       =>      esc_attr( '=' ),

                                                    'value'         =>      absint( $vendor_id ),
                                                );
                }

                /**
                 *  Have POST ?
                 *  -----------
                 */
                $query                  =       new WP_Query( array_merge(

                                                    /**
                                                     *  1. First Args
                                                     *  -------------
                                                     */
                                                    array(

                                                        'post_type'         =>  esc_attr( 'venue-review' ),

                                                        'post_status'       =>  array( 'publish' ),

                                                        'posts_per_page'    =>  absint( '3' ),

                                                        'paged'             =>  absint( '1' ),

                                                        'orderby'           =>  esc_attr( 'post_date' ),

                                                        'order'             =>  esc_attr( 'DESC' ),

                                                    ),

                                                    /**
                                                     *  2. Have Meta Query ?
                                                     *  --------------------
                                                     */
                                                    parent:: _is_array( $meta_query )

                                                    ?   array(

                                                            'meta_query'        => array(

                                                                    'relation'  => 'AND',

                                                                    $meta_query,
                                                            )
                                                        )

                                                    :   []
                        
                                                ) );
                /**
                 *  Have Post ?
                 *  -----------
                 */
                if ( $query->have_posts() ){

                    $handler        .=      sprintf(  '<div id="%1$s">',  esc_attr( $unique_id )  );

                    while ( $query->have_posts() ) {  $query->the_post();

                        $handler        .=      apply_filters( 'sdweddingdirectory/rating', [

                                                    'post_id'       =>      absint( get_the_ID() )

                                                ] );
                    }

                    $handler        .=      '</div>';

                    /**
                     *  See More Button To Load Next Review
                     *  -----------------------------------
                     */
                    if( $query->max_num_pages > absint( '1' ) ){

                        /**
                         *  Have More Comments to Load this button
                         *  --------------------------------------
                         */
                        $handler        .=

                        sprintf(    '<div class="text-center">

                                        <a  href="javascript:" %1$s %2$s 

                                            class="btn btn-default btn-rounded loadmore" 

                                            id="load_more_%3$s" 

                                            data-load-rating-id="%3$s">%4$s</a>

                                    </div>',

                                    /**
                                     *  1. Venue Post ID
                                     *  ------------------
                                     */
                                    !   empty(  $venue_id  )

                                    ?   sprintf( 'data-venue-post-id="%1$s"',  absint( $venue_id )  )

                                    :   '',

                                    /**
                                     *  2. Vendor ID
                                     *  ------------
                                     */
                                    !   empty(  $vendor_id  )

                                    ?   sprintf( 'data-vendor-post-id="%1$s"',  absint( $vendor_id )  )

                                    :   '',

                                    /**
                                     *  3. Unique ID
                                     *  ------------
                                     */
                                    esc_attr( $unique_id ),

                                    /**
                                     *  4. Translation String
                                     *  ---------------------
                                     */
                                    esc_attr__( 'See more reviews', 'sdweddingdirectory-reviews' )
                        );
                    }
                    
                    /**
                     *  Reset Filter
                     *  ------------
                     */
                    wp_reset_postdata();

                    /**
                     *  Print Data
                     *  ----------
                     */
                    if(  $echo  ){

                        print       $handler;
                    }

                    /**
                     *  Return Data
                     *  -----------
                     */
                    else{

                        return      $handler;
                    }
                }
            }
        }

        /**
         *  Rating Submit Form
         *  ------------------
         */
        public static function rating_submit_form( $args = [] ){

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

                    'handle'            =>      '',

                    'rating_id'         =>      absint( '0' ),

                    'venue_id'        =>      absint( '0' ),

                    'echo'              =>      true,

                    'button_data'       =>      '',

                    'form_id'           =>      parent:: _rand()

                ] ) );

                /**
                 *  User Not Login
                 *  --------------
                 */
                if( ! is_user_logged_in() ){

                    /**
                     *  Couple Login Required !
                     *  -----------------------
                     */
                    $button_data    =

                    sprintf(    '<div class=""><a class="btn btn-primary" %1$s>%2$s</a></div>',

                                /**
                                 *  1. Model ID
                                 *  -----------
                                 */
                                apply_filters( 'sdweddingdirectory/couple-login/attr', '' ),

                                /**
                                 *  2. Translation Ready String
                                 *  ---------------------------
                                 */
                                esc_attr__( 'Couple Login Required', 'sdweddingdirectory-reviews' )
                    );
                }

                /**
                 *  If Is Couple
                 *  ------------
                 */
                elseif( parent:: is_couple() ){

                    $couple_id          =       parent:: post_id();

                    $couple_rating      =       self:: couple_already_publish_review( [

                                                    'venue_id'  =>  absint( $venue_id ),

                                                    'couple_id'   =>  absint( $couple_id )

                                                ] );

                    if( parent:: _is_array( $couple_rating ) &&  count( $couple_rating ) >= absint( '1' )  ){

                        $rating_id          =       absint( $couple_rating[ 0 ] );
                    }

                    /**
                     *  Rating Security
                     *  ---------------
                     */
                    $rating_security    =       esc_attr(  'security_' . $form_id  );

                    /**
                     *  Submit Button
                     *  -------------
                     */
                    $button_data    =

                    sprintf(    '<div class="">

                                    <button type="submit" class="btn btn-primary loader" id="submit-%5$s">%1$s</button>

                                    %2$s

                                    <input id="venue_id" type="hidden" value="%3$s" />

                                    <input id="rating_id" type="hidden" value="%4$s" />

                                </div>', 

                                /**
                                 *  1. Translation Ready String
                                 *  ---------------------------
                                 */
                                !   empty( $rating_id )

                                ?   esc_attr__( 'Update Review', 'sdweddingdirectory-reviews' )

                                :   esc_attr__( 'Submit Review ', 'sdweddingdirectory-reviews' ),

                                /**
                                 *  2. Have Security ?
                                 *  ------------------
                                 */
                                wp_nonce_field( $rating_security, $rating_security, true, false ),

                                /**
                                 *  3. Venue ID
                                 *  -------------
                                 */
                                absint( $venue_id ),

                                /**
                                 *  4. Rating ID
                                 *  ------------
                                 */
                                !   empty( $rating_id )

                                ?   absint( $rating_id )

                                :   '',

                                /**
                                 *  5. Form ID
                                 *  ----------
                                 */
                                $form_id
                    );
                }

                /**
                 *  Review Title
                 *  ------------
                 */
                $rating_title   =

                sprintf( '<input autocomplete="off" class="form-control" name="%1$s" id="%1$s" placeholder="%2$s" value="%3$s" required/>',

                    /**
                     *  1. ID
                     *  -----
                     */
                    esc_attr( 'sdweddingdirectory_venue_review_title' ),

                    /**
                     *  2. Placeholder
                     *  --------------
                     */
                    esc_attr__( 'Main reason for your rating', 'sdweddingdirectory-reviews' ),

                    /**
                     *  3. Have Title ?
                     *  ---------------
                     */
                    !   empty( $rating_id )

                    ?   esc_attr( get_the_title( absint( $rating_id ) ) )

                    :   ''
                );

                /**
                 *  Textarea
                 *  --------
                 */
                $rating_comment     =   

                sprintf( '<textarea class="form-control" name="%1$s" id="%1$s" rows="5" placeholder="%2$s" required>%3$s</textarea>',

                    /**
                     *  1. ID
                     *  -----
                     */
                    esc_attr( 'sdweddingdirectory_venue_review_comment' ),

                    /**
                     *  2. Placeholder
                     *  --------------
                     */
                    esc_attr__( 'Your Comments', 'sdweddingdirectory-reviews' ),

                    /**
                     *  3. Have Title ?
                     *  ---------------
                     */
                    !   empty( $rating_id )

                    ?   esc_attr( get_post_meta( $rating_id, sanitize_key( 'couple_comment' ), true ) )

                    :   ''
                );

                /**
                 *  Rating Section
                 *  --------------
                 */
                $handle       .=

                sprintf(   '<form class="sdweddingdirectory_couple_update_rating" id="%9$s" method="post">

                                <div class="row rating-stars-wrap">%1$s %2$s %3$s %4$s %5$s</div>

                                <div class="row mt-4">

                                    <div class="col-md-12 mb-0">

                                        <div class="mb-3">%6$s</div>

                                    </div>

                                    <div class="col-md-12 mb-0">

                                        <div class="mb-3">%7$s</div>

                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-md-12 mb-0">%8$s</div>

                                </div>

                            </form>',

                            /**
                             *  1. Rating : Quality of Service
                             *  ---------------------------
                             */
                            self:: venue_review_input( array(

                                'icon'          =>      apply_filters( 'sdweddingdirectory/rating/icon/quality-of-service',

                                                            '<i class="fa fa-smile-o"></i>'
                                                        ),

                                'id'            =>      esc_attr( 'quality_service' ),

                                'lable'         =>      esc_attr__( 'Quality of Service', 'sdweddingdirectory-reviews' ),

                                'post_id'       =>      $rating_id,

                            ) ),

                            /**
                             *  2. Rating : Facilities
                             *  ----------------------
                             */
                            self:: venue_review_input( array(

                                'icon'          =>      apply_filters( 'sdweddingdirectory/rating/icon/responsiveness',

                                                            '<i class="fa fa-exchange"></i>'
                                                        ),

                                'id'            =>      esc_attr( 'facilities' ),

                                'lable'         =>      esc_attr__( 'Responsiveness', 'sdweddingdirectory-reviews' ),

                                'post_id'       =>      $rating_id

                            ) ),

                            /**
                             *  3. Rating : Staff
                             *  -----------------
                             */
                            self:: venue_review_input( array(

                                'icon'          =>      apply_filters( 'sdweddingdirectory/rating/icon/professionalism',

                                                            '<i class="fa fa-male"></i>'
                                                        ),

                                'id'            =>      esc_attr( 'staff' ),

                                'lable'         =>      esc_attr__( 'Professionalism', 'sdweddingdirectory-reviews' ),

                                'post_id'       =>      $rating_id

                            ) ),

                            /**
                             *  4. Rating : Flexibility
                             *  -----------------------
                             */
                            self:: venue_review_input( array(

                                'icon'          =>      apply_filters( 'sdweddingdirectory/rating/icon/flexibility',

                                                            '<i class="fa fa-sliders"></i>'
                                                        ),

                                'id'            =>      esc_attr( 'flexibility' ),

                                'lable'         =>      esc_attr__( 'Flexibility', 'sdweddingdirectory-reviews' ),

                                'post_id'       =>      $rating_id

                            ) ),

                            /**
                             *  5. Rating : Value of money
                             *  --------------------------
                             */
                            self:: venue_review_input( array(

                                'icon'          =>      apply_filters( 'sdweddingdirectory/rating/icon/value-for-money',

                                                            '<i class="fa fa-dollar"></i>'
                                                        ),

                                'id'            =>      esc_attr( 'value_of_money' ),

                                'lable'         =>      esc_attr__( 'Value for Money', 'sdweddingdirectory-reviews' ),

                                'post_id'       =>      $rating_id

                            ) ),

                            /**
                             *  6. Rating Title
                             *  ---------------
                             */
                            $rating_title,

                            /**
                             *  7. Rating Comment
                             *  -----------------
                             */
                            $rating_comment,

                            /**
                             *  8. Button
                             *  ---------
                             */
                            $button_data,

                            /**
                             *  9. Form ID
                             *  ----------
                             */
                            $form_id
                );

                /**
                 *  Print Data
                 *  ----------
                 */
                if(  $echo  ){

                    print       $handle;
                }

                /**
                 *  Return Data
                 *  -----------
                 */
                else{

                    return      $handle;
                }
            }
        }
    } 

    /**
     *  Review Database OJB
     *  -------------------
     */
    SDWeddingDirectory_Reviews_Database::get_instance();
}