<?php
/**
 *  Filter & Hooks
 *  --------------
 */
if( ! class_exists( 'SDWeddingDirectory_WishList_Filters' ) && class_exists( 'SDWeddingDirectory_WishList_Database' ) ){

    /**
     *  Filter & Hooks
     *  --------------
     */
    class SDWeddingDirectory_WishList_Filters extends SDWeddingDirectory_WishList_Database{

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
             *  Load one by one shortcode file
             *  ------------------------------
             */
            foreach ( glob( plugin_dir_path( __FILE__ ) . '/*/index.php' ) as $file ) {
           
                require_once $file;
            }

            /**
             *  Load Script 
             *  -----------
             */
            add_action( 'wp_enqueue_scripts', array( $this, 'sdweddingdirectory_script' ) );

            /**
             *  8. Wishlist Page Box - Layout 
             *  -----------------------------
             */
            add_action( 'sdweddingdirectory/wishlist/category-widget', [ $this, 'category_widget' ], absint( '10' ), absint( '1' ) );

            /**
             *  11. Venue Header Section Left Content [ Venue In Wishlist ]
             *  ---------------------------------------------------------------
             */
            add_action( 'sdweddingdirectory/venue/header/left/overview', [ $this, 'venue_in_wishlist' ], absint( '60' ), absint( '1' ) );

            /**
             *  12. Vendor Header Section Left Content [ Venue In Wishlist ]
             *  --------------------------------------------------------------
             */
            add_action( 'sdweddingdirectory/vendor/header/left/overview', [ $this, 'venue_in_wishlist' ], absint( '60' ), absint( '1' ) );

            /**
             *  1. Post / Wishlist
             *  ------------------
             */
            add_filter( 'sdweddingdirectory/post/wishlist', [ $this, 'wishlist_button' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  1. Load Script
         *  --------------
         */
        public static function sdweddingdirectory_script(){

            /**
             *  Make sure user login + is couple
             *  --------------------------------
             */
            if( parent:: is_couple() && parent:: dashboard_page_set( 'vendor-manager' ) || ! isset( $_GET['dashboard'] ) ){

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
         *  1. Wishlist Button
         *  ------------------
         */
        public static function wishlist_button( $args = [] ){

            /**
             *  Make sure have args
             *  -------------------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( wp_parse_args( $args, [

                    'post_id'           =>      absint( '0' ),

                    'layout'            =>      absint( '1' ),

                    'handler'           =>      '',

                ] ) );

                /**
                 *  Make sure post id not emtpy!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return;
                }

                /**
                 *  Helper
                 *  ------
                 */
                extract( [

                    'is_venue_post'       =>      get_post_type( absint( $post_id ) ) == esc_attr( 'venue' ),

                    'is_vendor_post'        =>      parent:: venue_author_is_vendor( absint( $post_id ) ),

                    'heart_icon'            =>      '<i class="fa fa-heart-o"></i>',

                    'heart_icon_fill'       =>      '<i class="fa fa-heart"></i>'

                ] );

                /**
                 *  Check all value is valid ? that mean this venue is created [ VENDOR ] author
                 *  ------------------------------------------------------------------------------
                 */
                if(  $is_venue_post  &&  $is_vendor_post ){
                    
                    /**
                     *  If User Not Login then showing login popup
                     *  ------------------------------------------
                     */
                    if( ! is_user_logged_in() ){

                        /**
                         *  Handler
                         *  -------
                         */
                        $handler    .=      sprintf(   '<a class="favorite" %1$s>%2$s</a>',

                                                        /**
                                                         *  1. Couple Login Popup
                                                         *  ---------------------
                                                         */
                                                        apply_filters( 'sdweddingdirectory/couple-login/attr', '' ),

                                                        /**
                                                         *  2. Icon
                                                         *  -------
                                                         */
                                                        $heart_icon
                                            );
                    }

                    /**
                     *  This user is login
                     *  ------------------
                     */
                    else{

                        /**
                         *  Extract Variable
                         *  ----------------
                         */
                        extract( [

                            'is_admin'          =>      current_user_can( 'administrator' ),

                            'is_vendor'         =>      parent:: is_vendor(),

                            'is_couple'         =>      parent:: is_couple(),

                        ] );

                        /**
                         *  Make sure it's couple user
                         *  --------------------------
                         */
                        if( $is_couple && ! ( $is_vendor || $is_admin ) ){

                            /**
                             *  Helper
                             *  ------
                             */
                            extract( [

                                'my_wishlist'       =>      parent:: get_wishlist(),

                                'in_wishlist'       =>      false,

                            ] );

                            /**
                             *  Is Array
                             *  --------
                             */
                            if( parent:: _is_array( $my_wishlist ) ){

                                foreach ( $my_wishlist as $key => $value ) {

                                    /**
                                     *  Extract Args
                                     *  ------------
                                     */
                                    extract( $value );
                                  
                                    /**
                                     *  Match Post ?
                                     *  ------------
                                     */
                                    if( $wishlist_venue_id == $post_id ){

                                        $in_wishlist        =       true;
                                    }
                                }
                            }

                            /**
                             *  1. In Wishlist
                             *  --------------
                             */
                            if( $in_wishlist ){

                                $handler        .=      sprintf(    '<a href="javascript:" class="wishlist-icon favorite active" data-post-id="%1$s">%2$s</a>',

                                                                    /**
                                                                     *  1. Venue ID
                                                                     *  -------------
                                                                     */
                                                                    absint( $post_id ),

                                                                    /**
                                                                     *  2. Icon
                                                                     *  -------
                                                                     */
                                                                    $heart_icon_fill
                                                        );
                            }

                            /**
                             *  Not in Wishlist
                             *  ---------------
                             */
                            else{

                                $handler        .=      sprintf(    '<a href="javascript:" data-post-id="%1$s" class="wishlist-icon favorite">%2$s</a>',

                                                                    /**
                                                                     *  1. Venue ID
                                                                     *  -------------
                                                                     */
                                                                    absint( $post_id ),

                                                                    /**
                                                                     *  2. Icon
                                                                     *  -------
                                                                     */
                                                                    $heart_icon
                                                        );
                            }
                        }

                        /**
                         *  Is Admin / Vendor
                         *  -----------------
                         */
                        elseif( $is_vendor || $is_admin ){

                            /**
                             *  Is Vendor or Admin ?
                             *  --------------------
                             */
                            $handler        .=      sprintf( '<a class="favorite disabled" href="javascript:">%1$s</a>', 

                                                            /**
                                                             *  1. Icon
                                                             *  -------
                                                             */
                                                            $heart_icon
                                                    );
                        }
                    }
                }

                /**
                 *  Return Data
                 *  -----------
                 */
                return      $handler;
            }
        }

        /**
         *  3. Wishlist Page Box - Layout 
         *  -----------------------------
         */
        public static function category_widget( $args = [] ){

            /**
             *  Have Data ?
             *  -----------
             */
            if( parent:: _have_data( $args ) ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( wp_parse_args( $args, [

                    'row_class'             =>      esc_attr( 'row row-cols-1 row-cols-md-4 row-cols-sm-2' ),

                    'venue_category'      =>      apply_filters( 'sdweddingdirectory/tax/parent', esc_attr( 'vendor-category' ) ),

                    'have_data'             =>      parent:: get_wishlist_data( 'wishlist_venue_category' )

                ] ) );

                /**
                 *  Load All Categories
                 *  -------------------
                 */
                if( parent:: _is_array( $venue_category ) ){

                    printf( '<div class="%1$s">', $row_class );

                    foreach ( $venue_category as $key => $value ) {

                        /**
                         *  1. Have Backend Wishlist ?
                         *  --------------------------
                         */
                        $_condition_1   =   parent:: _is_array( $have_data );

                        $_condition_2   =   $_condition_1 && array_key_exists( absint( $key ), array_flip( $have_data ) );

                        /**
                         *  Have Backend Wishlist Category ID Set with Main Category ?
                         *  ----------------------------------------------------------
                         */
                        if( $_condition_1 && $_condition_2 ){

                            /**
                             *  Layout 1
                             *  --------
                             */
                            parent:: sdweddingdirectory_wishlist_layout( array(

                                'key'       =>    absint( $key ),

                                'layout'    =>    absint( '1' )

                            ) );

                        }else{

                            /**
                             *  Layout 2
                             *  --------
                             */
                            parent:: sdweddingdirectory_wishlist_layout( array(

                                'key'       =>    absint( $key ),

                                'layout'    =>    absint( '2' )

                            ) );
                        }
                    }

                    ?></div><?php
                }

            }
        }

        /**
         *  10. Venue Header Section Left Content [ Venue Views ]
         *  ---------------------------------------------------------
         */
        public static function venue_in_wishlist( $args = [] ){

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
                 *  Couple love venue
                 *  -------------------
                 */
                $_couple_love_venue       =       parent:: couple_wish_venue( [ 'post_id'  =>  absint( $post_id ) ] );

                /**
                 *  Have View More then 1 ?
                 *  -----------------------
                 */
                if( $_couple_love_venue >= absint( '1' ) ){

                    /**
                     *  Couple love the venue
                     *  -----------------------
                     */
                    printf( '<li class="d-flex align-items-center"><i class="fa fa-heart-o"></i> %1$s %2$s</li>',

                        /**
                         *  1. Page View
                         *  ------------
                         */
                        absint( $_couple_love_venue ),

                        /**
                         *  2. Translation Ready String
                         *  ---------------------------
                         */
                        esc_attr__( 'Favorite', 'sdweddingdirectory-wishlist' )
                    );
                }
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_WishList_Filters:: get_instance();
}
