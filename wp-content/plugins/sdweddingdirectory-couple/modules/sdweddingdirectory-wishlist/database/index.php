<?php
/**
 *  SDWeddingDirectory - Wishlist Database
 *  ------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_WishList_Database' ) && class_exists( 'SDWeddingDirectory_Form_Tabs' ) ){

    /**
     *  SDWeddingDirectory - Wishlist Database
     *  ------------------------------
     */
    class SDWeddingDirectory_WishList_Database extends SDWeddingDirectory_Form_Tabs{

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
         *  File Version
         *  ------------
         */
        public static function _file_version( $file = '' ){

            /**
             *  Is Empty ?
             *  ----------
             */
            if( empty( $file ) ){

                /**
                 *  Get Style Version
                 *  -----------------
                 */
                return      esc_attr( SDWEDDINGDIRECTORY_WISHLIST_VERSION );
            }

            else{

                /*
                 *  For File Save timestrap version to clear the catch auto
                 *  -------------------------------------------------------
                 *  # https://developer.wordpress.org/reference/functions/wp_enqueue_style/#comment-6386
                 *  ------------------------------------------------------------------------------------
                 */
                return      esc_attr( SDWEDDINGDIRECTORY_WISHLIST_VERSION ) . '.' . absint( filemtime(  $file ) );
            }
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  1. Vendor Manager Layout
             *  ------------------------
             */
            add_action( 'sdweddingdirectory_wishlist_layout', [ $this, 'sdweddingdirectory_wishlist_layout' ], absint( '10' ), absint( '1' ) );

            /**
             *  2. Wishlist Choose Dropdown
             *  ---------------------------
             */
            add_filter( 'sdweddingdirectory_wishlist_choose_dropdown', [ $this, 'sdweddingdirectory_wishlist_choose_dropdown_markup' ], absint( '10' ), absint( '1' ) );

            /**
             *  3. Hire Vendor Layout
             *  ---------------------
             */
            add_action( 'sdweddingdirectory_hire_vendor_layout', [ $this, 'sdweddingdirectory_hire_vendor_layout_markup' ] );
        }

        /**
         *  Count Venue Post ID in total couple
         *  -------------------------------------
         */
        public static function couple_wish_venue( $args = [] ){

            global $wp_query, $post;

            /**
             *  Have Data ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( wp_parse_args( $args, array(

                    'meta_key'     =>  esc_attr( 'wishlist_venue_id' ),

                    'post_id'      =>  absint( '0' )

                ) ) );

                /**
                 *  Have Post ID ?
                 *  --------------
                 */
                if( empty( $post_id ) ){

                    return  absint( '0' );
                }

                /**
                 *  Handler
                 *  -------
                 */
                $_counter   =   absint( '0' );

                /**
                 *  Create WP_Query
                 *  ---------------
                 */
                $_couple_wish_venue_query  =   new WP_Query( array(

                        'post_type'         =>  esc_attr( 'couple' ), 

                        'post_status'       =>  esc_attr( 'publish' ),
                ) );

                /**
                 *  Have Data ?
                 *  -----------
                 */
                if ( $_couple_wish_venue_query->have_posts() ){

                    while ( $_couple_wish_venue_query->have_posts() ){  $_couple_wish_venue_query->the_post(); 

                        /**
                         *  Current Venue Post ID
                         *  -----------------------
                         */
                        $_couple_post_id        =   absint( get_the_ID() );

                        $_get_couple_wishlist   =   get_post_meta(

                                                        /**
                                                         *  1. Couple Post ID
                                                         *  -----------------
                                                         */
                                                        absint( $_couple_post_id ), 

                                                        /**
                                                         *  2. Couple Wishlist Meta Key
                                                         *  ---------------------------
                                                         */
                                                        sanitize_key( 'sdweddingdirectory_wishlist' ), 

                                                        /**
                                                         *  3. TRUE
                                                         *  -------
                                                         */
                                                        true 
                                                    );
                        /**
                         *  Have Data ?
                         *  -----------
                         */
                        if( parent:: _is_array( $_get_couple_wishlist ) ){

                            foreach( $_get_couple_wishlist as $key => $value ){

                                if( $value[ $meta_key ]  == absint( $post_id ) ){

                                    $_counter++;
                                }
                            }
                        }
                    }
                }

                /**
                 *  Reset Query
                 *  -----------
                 */
                if ( isset( $_couple_wish_venue_query ) && $_couple_wish_venue_query !== '' ) {

                    wp_reset_postdata();
                }

                /**
                 *  Return : Total Number of Couple Wishlist for this venue
                 *  ---------------------------------------------------------
                 */
                return      absint( $_counter );

            }else{

                /**
                 *  Return Empty!
                 *  -------------
                 */
                return  absint( '0' );
            }
        }

        /**
         *  Wishlist Choose Dropdown List
         *  -----------------------------
         */
        public static function sdweddingdirectory_wishlist_choose_dropdown_markup( $args = [] ){

            /**
             *  Options
             *  -------
             */
            return  array_merge( $args, array(

                        'unavailable'       =>  esc_attr__( 'Unavailable', 'sdweddingdirectory-wishlist' ),

                        'not-a-good-fit'    =>  esc_attr__( 'Not a Good Fit', 'sdweddingdirectory-wishlist' ),

                        'evaluating'        =>  esc_attr__( 'Evaluating', 'sdweddingdirectory-wishlist' ),

                        'hired'             =>  esc_attr__( 'Hired', 'sdweddingdirectory-wishlist' ),

                    ) );
        }

        /**
         *  Wishlist Layout
         *  ---------------
         */
        public static function sdweddingdirectory_wishlist_layout( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( wp_parse_args( $args, array(

                    'key'       =>    absint( '0' ),

                    'layout'    =>    absint( '1' ),

                    'print'     =>    true

                ) ) );

                /**
                 *  Is Empty ?
                 *  ----------
                 */
                if( empty( $key ) ){

                    return;
                }

                /**
                 *  Collection of Data
                 *  ------------------
                 */
                $_get_data  =   '';

                /**
                 *  1. Layout 1
                 *  -----------
                 */
                if( $layout == absint( '1' ) ){

                    $_get_data  .=

                    sprintf('<div class="col">

                                <div class="d-flex flex-column dash-categories text-center py-3 selected" %1$s>

                                    <div class="edit">

                                        <a href="%3$s" target="_blank"><i class="fa fa-pencil"></i></a>

                                    </div>
                                    
                                    <div class="head">

                                        <i class="%4$s"></i>

                                        <p>%5$s</p>

                                    </div>

                                    <div class="wishlist-box-content">%6$s %7$s</div>

                                </div>

                            </div>',

                            /**
                             *  1. Category Image
                             *  -----------------
                             */
                            sprintf( 'style="background: url(%1$s) no-repeat; background-size: cover;"',

                                apply_filters( 'sdweddingdirectory/term/image', [ 'term_id' =>  $key, 'taxonomy' => 'vendor-category' ] )
                            ),

                            /**
                             *  2. Category Slug
                             *  ----------------
                             */
                            sanitize_title( get_term(

                                /**
                                 *  1. Term ID
                                 *  ----------
                                 */
                                absint( $key ),

                                /**
                                 *  2. Vendor Category Slug
                                 *  ------------------------
                                 */
                                esc_attr( 'vendor-category' )

                            )->slug ),

                            /**
                             *  3. Vendor Category Archive Link
                             *  -------------------------------
                             */
                            esc_url( get_term_link( absint( $key ), 'vendor-category' ) ),

                            /**
                             *  4. Category Icon
                             *  ----------------
                             */
                            apply_filters( 'sdweddingdirectory/term/icon', [

                                'term_id'       =>      absint( $key ),

                                'taxonomy'      =>      'vendor-category',

                            ] ),

                            /**
                             *  5. Category Name
                             *  ----------------
                             */
                            esc_attr( get_term(

                                /**
                                 *  1. Term ID
                                 *  ----------
                                 */
                                absint( $key ),

                                /**
                                 *  2. Vendor Category Slug
                                 *  ------------------------
                                 */
                                esc_attr( 'vendor-category' )

                            )->name ),

                            /**
                             *  6. Total Added Category Venue
                             *  -------------------------------
                             */
                            self:: count_category_venue( absint( $key ) ),

                            /**
                             *  7. Category Vendor Hire at least one ?
                             *  --------------------------------------
                             */
                            self:: category_vendor_hire( absint( $key ) ) >= absint( '1' )

                            ?   parent:: dashboard_page_set( 'vendor-manager' )

                                ?   sprintf(  '<button data-show-tab="#%2$s"

                                                class="btn btn-sm btn-success btn-rounded sdweddingdirectory-hire-icon ms-1 _show_tab_">

                                                <i class="fa fa-check me-1"></i> %1$s</button>',

                                        /**
                                         *  1. Translation Ready String
                                         *  ---------------------------
                                         */
                                        esc_attr__( 'Hired', 'sdweddingdirectory-wishlist' ),

                                        /**
                                         *  2. Main Tab
                                         *  -----------
                                         */
                                        esc_attr( 'hired-tab' )
                                    )

                                :   sprintf(  '<a   class="btn btn-sm btn-success btn-rounded sdweddingdirectory-hire-icon ms-1" href="%2$s" target="_self">

                                                    <i class="fa fa-check me-1"></i> %1$s</a>',

                                        /**
                                         *  1. Translation Ready String
                                         *  ---------------------------
                                         */
                                        esc_attr__( 'Hired', 'sdweddingdirectory-wishlist' ),

                                        /**
                                         *  2. Main Tab
                                         *  -----------
                                         */
                                        apply_filters( 'sdweddingdirectory/couple-menu/page-link', esc_attr( 'vendor-manager' ) )
                                    )

                            :   '',

                            /**
                             *  8. Hired Tab
                             *  ------------
                             */
                            esc_attr( 'favorite-vendors-tab' ),

                            /**
                             *  9. Category Tab ID
                             *  ------------------
                             */
                            sanitize_key(  get_term_by( 'id', $key, 'vendor-category' )->name . '-tab' )
                    );
                }

                /**
                 *  Layout 2
                 *  --------
                 */
                if( $layout == absint( '2' ) ){

                    $_get_data  .=

                    sprintf( '<div class="col">

                                <a href="%1$s" target="_blank">
                                
                                    <div class="d-flex flex-column dash-categories text-center py-3">

                                        <div class="head mb-4"><i class="%2$s"></i> %3$s</div>

                                        <button class="btn btn-outline-white btn-rounded"><i class="fa fa-search me-2"></i>%4$s</button>

                                    </div>

                                </a>

                            </div>',

                            /**
                             *  1. Vendor Category Archive Link
                             *  -------------------------------
                             */
                            esc_url( get_term_link( absint( $key ), 'vendor-category' ) ),

                            /**
                             *  2. Category Icon
                             *  ----------------
                             */
                            apply_filters( 'sdweddingdirectory/term/icon', [

                                'term_id'       =>      absint( $key ),

                                'taxonomy'      =>      'vendor-category',

                            ] ),

                            /**
                             *  3. Category Name
                             *  ----------------
                             */
                            esc_attr( get_term(

                                /**
                                 *  1. Term ID
                                 *  ----------
                                 */
                                absint( $key ),

                                /**
                                 *  2. Vendor Category Slug
                                 *  ------------------------
                                 */
                                esc_attr( 'vendor-category' )

                            )->name ),

                            /**
                             *  4. Translation Ready String
                             *  ---------------------------
                             */
                            esc_attr__( 'Search', 'sdweddingdirectory-wishlist' )
                    );
                }

                /**
                 *  Print ?
                 *  -------
                 */
                if( $print ){

                    print   $_get_data;

                }else{

                    return  $_get_data;
                }
            }
        }

        /**
         *  1. Get Todo List Data
         *  ----------------------
         */
        public static function get_wishlist(){

            return parent:: get_data( sanitize_key( 'sdweddingdirectory_wishlist' ) );
        }

        /**
         *  2. Find list of data with return array
         *  --------------------------------------
         */
        public static function get_wishlist_data( $args = '' ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( empty( $args ) ){

                return;
            }

            $_return_data       =   [];

            $_get_data          =   self:: get_wishlist();

            $_create_new_array = [];

            if( parent:: _is_array( $_get_data ) ){

                foreach ( $_get_data as $key => $value) {

                    $_return_data[]  = $value[ sanitize_key( $args ) ];
                }
            }

            if( parent:: _is_array( $_return_data ) ){

                return array_unique( $_return_data );

            }else{

                return;
            }
        }

        /**
         *  Wishlist Unique Id to get value
         *  -------------------------------
         */
        public static function wishlist_unique_id_value( $unique_id = '', $find_value = '' ){

            if( parent:: _is_array( self:: get_wishlist() ) ){

                foreach ( self:: get_wishlist() as $key => $value ) {

                    extract( $value );

                    if( $wishlist_unique_id == $unique_id ){

                        return  $value[ $find_value ];
                    }
                }
            }
        }

        /**
         *  Wishlist : Count Category Venue
         *  ---------------------------------
         */
        public static function count_category_venue( $cat_id = '0' ){

            if( empty( $cat_id ) ){
                return;
            }

            $_counter   =   absint( '0' );

            if( parent:: _is_array( self:: get_wishlist() ) ){

                foreach ( self:: get_wishlist() as $key => $value ) {

                    extract( $value );

                    if( $cat_id == $wishlist_venue_category ){

                        $_counter++;
                    }
                }
            }

            /**
             *  Have at least : 1 wishlist category venue ?
             *  ---------------------------------------------
             */
            if( $_counter >= absint( '1' ) ){

                /**
                 *  Couple Wishlist
                 *  ---------------
                 */
                if( parent:: dashboard_page_set( 'vendor-manager' ) ){

                    return

                    sprintf(   '<button class="btn btn-sm btn-default btn-rounded sdweddingdirectory-heart-icon me-1 _show_tab_" 

                                        data-show-tab="#%2$s,#%3$s"><i class="fa fa-heart-o me-1"></i> %1$s</button>',

                                /**
                                 *  1. Counter
                                 *  ----------
                                 */
                                absint( $_counter ),

                                /**
                                 *  2. Hired Tab
                                 *  ------------
                                 */
                                esc_attr( 'favorite-vendors-tab' ),

                                /**
                                 *  3. Category Tab ID
                                 *  ------------------
                                 */
                                sanitize_key(  get_term_by( 'id', $cat_id, 'venue-type' )->name . '-tab' )
                    );
                }

                else{

                    return

                    sprintf(   '<a  class="btn btn-sm btn-default btn-rounded sdweddingdirectory-heart-icon me-1" href="%2$s" 

                                    target="_self"><i class="fa fa-heart-o me-1"></i> %1$s</a>',

                                /**
                                 *  1. Counter
                                 *  ----------
                                 */
                                absint( $_counter ),

                                /**
                                 *  2. Hired Tab
                                 *  ------------
                                 */
                                apply_filters( 'sdweddingdirectory/couple-menu/page-link', esc_attr( 'vendor-manager' ) )
                    );
                }
            }
        }

        /**
         *  Wishlist : Category Vendor Hire or Not ?
         *  ----------------------------------------
         */
        public static function category_vendor_hire( $cat_id = '0' ){

            if( empty( $cat_id ) ){
                return;
            }

            $_counter   =   absint( '0' );

            /**
             *  Have Wishlist ?
             *  ---------------
             */
            if( parent:: _is_array( self:: get_wishlist() ) ){

                foreach ( self:: get_wishlist() as $key => $value ) {

                    extract( $value );

                    if( $cat_id == $wishlist_venue_category ){

                        if( $wishlist_hire_vendor == esc_attr( 'hired' ) ){

                            $_counter++;
                        }
                    }
                }
            }

            return  absint( $_counter );
        }

        /**
         *  Count : Total Hire Category Vendor
         *  ----------------------------------
         */
        public static function count_hire_category_vendor(){

            $_venue_category =

            SDWeddingDirectory_Taxonomy:: get_taxonomy_depth(

                  /**
                   *  1. Venue Category Slug
                   *  ------------------------
                   */
                  esc_attr( 'venue-type' ),

                  /**
                   *  2. Parent
                   *  ---------
                   */
                  absint( '1' )
            );

            $_count_hire_category_vendor = absint( '0' );

            if( parent:: _is_array( $_venue_category ) ){

                foreach( $_venue_category as $key => $value ){

                    if( self:: category_vendor_hire( $key ) >= absint( '1' ) ){

                        $_count_hire_category_vendor++;
                    }
                }
            }

            return  absint( $_count_hire_category_vendor );
        }

        /**
         *  3. Find list of data with return array
         *  --------------------------------------
         */
        public static function get_wishlist_carousel_data(){

            $_get_data    = self:: get_wishlist();

            $_create_new_array = [];

            if( parent:: _is_array( $_get_data ) ){

                foreach ( $_get_data as $k => $v) {

                    $_create_new_array[ $v[ 'wishlist_venue_category' ] ][  $v[ 'wishlist_venue_id'  ] ]  =  $v[ 'wishlist_venue_id'  ];
                }
            }

            return $_create_new_array;
        }

        /**
         *  4. Number of Wishlist counter
         *  -----------------------------
         */
        public static function number_of_wishlist() {

            $get_wishlist = self:: get_wishlist();

            if(  parent:: _is_array( $get_wishlist )  ) {

                return count( $get_wishlist );
            }

            return absint( '0' );
        }

        /**
         *  Hire Vendor Layout
         *  ------------------ 
         */
        public static function sdweddingdirectory_hire_vendor_layout_markup(){

            /**
             *  Have Wishlist ?
             *  ---------------
             */
            if( parent:: _is_array( self:: get_wishlist() ) ){

                print '<div class="row row-cols-xxl-4 row-cols-xl-4 row-cols-lg-3 row-cols-md-2 row-cols-sm-2 row-cols-1">';

                foreach ( self:: get_wishlist() as $key => $value ) {

                    /**
                     *  Extract Args
                     *  ------------
                     */
                    extract( $value );

                    /**
                     *  Check Is Hire Vendor ?
                     *  ----------------------
                     */
                    if( $wishlist_hire_vendor == esc_attr( 'hired' ) ){

                        /**
                         *  Venue Layout
                         *  --------------
                         */
                        print   apply_filters( 'sdweddingdirectory/venue/post', [

                                    'layout'    =>  absint( '4' ),

                                    'post_id'   =>  absint( $wishlist_venue_id ),

                                    'unique_id' =>  absint( $wishlist_unique_id )

                                ] );
                    }
                }

                print '</div>';
            }
        }

        /**
         *  Overall fit venue
         *  -------------------
         */
        public static function overall_fit_string( $unique_id = '' ){

            /**
             *  Make sure unique id not empty!
             *  ------------------------------
             */
            if( empty( $unique_id ) ){

                return  esc_attr__( 'Overall fit', 'sdweddingdirectory-wishlist' );
            }

            $_love_vendor   =   array(

                                    /**
                                     *  Default String
                                     *  --------------
                                     */
                                    '0' => esc_attr__( 'Overall fit', 'sdweddingdirectory-wishlist' ),

                                    /**
                                     *  1. POOR Wishlist Rating String
                                     *  ------------------------------
                                     */
                                    '1' =>  esc_attr__( 'POOR', 'sdweddingdirectory-wishlist' ),

                                    /**
                                     *  2. AVERAGE Wishlist Rating String
                                     *  ---------------------------------
                                     */
                                    '2' =>  esc_attr__( 'AVERAGE', 'sdweddingdirectory-wishlist' ),

                                    /**
                                     *  3. GOOD Wishlist Rating String
                                     *  ------------------------------
                                     */
                                    '3' =>  esc_attr__( 'GOOD', 'sdweddingdirectory-wishlist' ),

                                    /**
                                     *  4. VERY GOOD Wishlist Rating String
                                     *  -----------------------------------
                                     */
                                    '4' =>  esc_attr__( 'VERY GOOD', 'sdweddingdirectory-wishlist' ),

                                    /**
                                     *  5. EXCELLENT Wishlist Rating String
                                     *  -----------------------------------
                                     */
                                    '5' =>  esc_attr__( 'EXCELLENT', 'sdweddingdirectory-wishlist' ),
                                );

            /**
             *  Return Overall fit listig string
             *  --------------------------------
             */
            return  esc_attr(

                        /**
                         *  Get list of string
                         *  ------------------
                         */
                        $_love_vendor[

                            /**
                             *  Get rating number
                             *  -----------------
                             */
                            absint( self:: couple_fit_venue_rating( absint( $unique_id ) ) )
                        ]
                    );
        }

        /**
         *  Couple updated over all fit venue rating
         *  ------------------------------------------
         */
        public static function couple_fit_venue_rating( $unique_id = '' ){

            /**
             *  Make sure unique id not empty!
             *  ------------------------------
             */
            if( empty( $unique_id ) ){

                return  absint( '0' );
            }

            /**
             *  Get rating number
             *  -----------------
             */
            return  absint( self:: wishlist_unique_id_value(

                        /**
                         *  1. Unique Number
                         *  ----------------
                         */
                        absint( $unique_id ),

                        /**
                         *  2. meta key
                         *  -----------
                         */
                        esc_attr( 'wishlist_rating' )

                    ) );
        }

        /**
         *  Couple wish estimate price for this venue
         *  -------------------------------------------
         */
        public static function couple_estimate_price( $unique_id = '' ){

            /**
             *  Make sure unique id not empty!
             *  ------------------------------
             */
            if( empty( $unique_id ) ){

                return  absint( '0' );
            }

            /**
             *  Get rating number
             *  -----------------
             */
            return  absint( self:: wishlist_unique_id_value(

                        /**
                         *  1. Unique Number
                         *  ----------------
                         */
                        absint( $unique_id ),

                        /**
                         *  2. meta key
                         *  -----------
                         */
                        esc_attr( 'wishlist_estimate_price' )

                    ) );
        }

        /**
         *  Couple Notes for Wishlist Venue
         *  ---------------------------------
         */
        public static function couple_wishlist_note( $unique_id = '' ){

            /**
             *  Make sure unique id not empty!
             *  ------------------------------
             */
            if( empty( $unique_id ) ){

                return;
            }

            /**
             *  Get rating number
             *  -----------------
             */
            return  esc_attr( self:: wishlist_unique_id_value(

                        /**
                         *  1. Unique Number
                         *  ----------------
                         */
                        absint( $unique_id ),

                        /**
                         *  2. meta key
                         *  -----------
                         */
                        esc_attr( 'wishlist_note' )

                    ) );
        }
    }

    /**
     *  SDWeddingDirectory - Wishlist Database
     *  ------------------------------
     */
    SDWeddingDirectory_WishList_Database:: get_instance();
}