<?php
/**
 *  Filter & Hooks
 *  --------------
 */
if( ! class_exists( 'SDWeddingDirectory_WishList_Left_Widget_Filters' ) && class_exists( 'SDWeddingDirectory_WishList_Filters' ) ){

    /**
     *  Filter & Hooks
     *  --------------
     */
    class SDWeddingDirectory_WishList_Left_Widget_Filters extends SDWeddingDirectory_WishList_Filters{

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
             *  1. Wishlist Button Layout
             *  -------------------------
             */
            add_action( 'sdweddingdirectory/venue/header/right/overview', [ $this, 'venue_singular_wishlist_button' ],  absint( '50' ), absint( '1' ) );

            /**
             *  2. Hire Button Layout
             *  ---------------------
             */
            add_action( 'sdweddingdirectory/venue/header/right/overview',  [ $this, 'venue_singular_hire_button' ],  absint( '60' ), absint( '1' )  );

            /**
             *  3. Vendor Wishlist Button Layout
             *  -------------------------------
             */
            add_action( 'sdweddingdirectory/vendor/header/right/overview', [ $this, 'venue_singular_wishlist_button' ],  absint( '50' ), absint( '1' ) );

            /**
             *  4. Vendor Hire Button Layout
             *  ----------------------------
             */
            add_action( 'sdweddingdirectory/vendor/header/right/overview',  [ $this, 'venue_singular_hire_button' ],  absint( '60' ), absint( '1' )  );
        }

        /**
         *  1. Wishlist Button Layout
         *  -------------------------
         */
        public static function venue_singular_wishlist_button( $args = [] ){

            /**
             *  Make sure it's array
             *  --------------------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'post_id'               =>      absint( '0' ),

                ] ) );

                /**
                 *  Make sure post id not Empty
                 *  ---------------------------
                 */
                if( empty( $post_id ) ){

                    return;
                }

                /**
                 *  Extract
                 *  -------
                 */
                extract( [

                    'string'                =>      esc_attr__( 'Save', 'sdweddingdirectory-wishlist' ),

                    'is_venue_post'       =>      get_post_type( absint( $post_id ) ) == esc_attr( 'venue' ),

                    'is_vendor_post_type'   =>      get_post_type( absint( $post_id ) ) == esc_attr( 'vendor' ),

                    'is_vendor_post'        =>      parent:: venue_author_is_vendor( $post_id ),

                    'handler'               =>      '',

                    'echo'                  =>      true,

                    'heart_icon'            =>      '<i class="fa fa-heart-o"></i>',

                    'heart_icon_fill'       =>      '<i class="fa fa-heart"></i>'

                ] );

                /**
                 *  Check all value is valid ? that mean this venue is created [ VENDOR ] author
                 *  ------------------------------------------------------------------------------
                 */
                if( ( $is_venue_post && $is_vendor_post ) || $is_vendor_post_type ){

                    /**
                     *  If User Not Login then showing login popup
                     *  ------------------------------------------
                     */
                    if( ! is_user_logged_in() ){

                        $handler        .=      sprintf(   '<li class="d-flex align-items-center">

                                                                <a class="d-flex align-items-center" %1$s> %2$s <span>%3$s</span> </a>

                                                            </li>',

                                                            apply_filters( 'sdweddingdirectory/couple-login/attr', '' ),

                                                            $heart_icon,

                                                            esc_attr( $string )
                                                );
                    }

                    /**
                     *  User login actions
                     *  ------------------
                     */
                    else{

                        extract( [

                            'is_admin'              =>      current_user_can( 'administrator' ),

                            'is_vendor'             =>      parent:: is_vendor(),

                            'is_couple'             =>      parent:: is_couple()

                        ] );

                        /**
                         *  Is Couple ?
                         *  -----------
                         */
                        if( $is_couple && ! ( $is_vendor || $is_admin ) ){

                            /**
                             *  Check user meta for saved state
                             *  -------------------------------
                             */
                            $in_wishlist = function_exists( 'sdwd_profile_user_has_item' )
                                         ? sdwd_profile_user_has_item( 'sdwd_saved_profiles', $post_id )
                                         : false;

                            if( $in_wishlist ){

                                $handler        .=      sprintf(    '<li class="d-flex align-items-center">

                                                                        <a  href="javascript:"

                                                                            data-post-id="%1$s"

                                                                            class="wishlist-icon active d-flex align-items-center">

                                                                            %2$s <span>%3$s</span>

                                                                        </a>

                                                                    </li>',

                                                                    absint( $post_id ),

                                                                    $heart_icon_fill,

                                                                    esc_attr__( 'Saved', 'sdweddingdirectory-wishlist' )
                                                        );
                            }

                            else{

                                $handler        .=      sprintf(    '<li class="d-flex align-items-center">

                                                                        <a  href="javascript:"

                                                                            data-post-id="%1$s"

                                                                            class="wishlist-icon d-flex align-items-center">

                                                                            %2$s <span>%3$s</span>

                                                                        </a>

                                                                    </li>',

                                                                    absint( $post_id ),

                                                                    $heart_icon,

                                                                    esc_attr( $string )
                                                        );
                            }
                        }

                        /**
                         *  If is Vendor OR Admin
                         *  ---------------------
                         */
                        elseif( $is_vendor || $is_admin ){

                            $handler        .=      sprintf(    '<li class="d-flex align-items-center">

                                                                    <a  href="javascript:" class="disabled d-flex align-items-center">

                                                                        %1$s <span>%2$s</span>

                                                                    </a>

                                                                </li>',

                                                                $heart_icon,

                                                                esc_attr( $string )
                                                    );
                        }
                    }
                }

                /**
                 *  Print ?
                 *  -------
                 */
                if( $echo ){

                    print           $handler;
                }

                else{

                    return          $handler;
                }
            }
        }

        /**
         *  2. Hire Button Layout
         *  ---------------------
         */
        public static function venue_singular_hire_button( $args = [] ){

            /**
             *  Make sure it's array
             *  --------------------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'post_id'               =>      absint( '0' ),

                ] ) );

                /**
                 *  Make sure post id not Empty
                 *  ---------------------------
                 */
                if( empty( $post_id ) ){

                    return;
                }

                /**
                 *  Extract
                 *  -------
                 */
                extract( [

                    'string'                =>      esc_attr__( 'Hired ?', 'sdweddingdirectory-wishlist' ),

                    'is_venue_post'       =>      get_post_type( absint( $post_id ) ) == esc_attr( 'venue' ),

                    'is_vendor_post_type'   =>      get_post_type( absint( $post_id ) ) == esc_attr( 'vendor' ),

                    'is_vendor_post'        =>      parent:: venue_author_is_vendor( $post_id ),

                    'handler'               =>      '',

                    'echo'                  =>      true,

                    'heart_icon'            =>      '<i class="fa fa-handshake-o"></i>',

                    'heart_icon_fill'       =>      '<i class="fa fa-check-circle"></i>'

                ] );

                /**
                 *  Check all value is valid ? that mean this venue is created [ VENDOR ] author
                 *  ------------------------------------------------------------------------------
                 */
                if( ( $is_venue_post && $is_vendor_post ) || $is_vendor_post_type ){

                    if( ! is_user_logged_in() ){

                        $handler        .=      sprintf(   '<li class="d-flex align-items-center">

                                                                <a class="d-flex align-items-center" %1$s> %2$s <span>%3$s</span> </a>

                                                            </li>',

                                                            apply_filters( 'sdweddingdirectory/couple-login/attr', '' ),

                                                            $heart_icon,

                                                            esc_attr( $string )
                                                );
                    }

                    else{

                        extract( [

                            'is_admin'              =>      current_user_can( 'administrator' ),

                            'is_vendor'             =>      parent:: is_vendor(),

                            'is_couple'             =>      parent:: is_couple()

                        ] );

                        if( $is_couple && ! ( $is_vendor || $is_admin ) ){

                            /**
                             *  Check user meta for hired state
                             *  -------------------------------
                             */
                            $in_wishlist = function_exists( 'sdwd_profile_user_has_item' )
                                         ? sdwd_profile_user_has_item( 'sdwd_hired_profiles', $post_id )
                                         : false;

                            if( $in_wishlist ){

                                $handler        .=      sprintf(    '<li class="d-flex align-items-center">

                                                                        <a  href="javascript:"

                                                                            data-post-id="%1$s"

                                                                            class="hire-icon active d-flex align-items-center">

                                                                            %2$s <span>%3$s</span>

                                                                        </a>

                                                                    </li>',

                                                                    absint( $post_id ),

                                                                    $heart_icon_fill,

                                                                    esc_attr__( 'Hired', 'sdweddingdirectory-wishlist' )
                                                        );
                            }

                            else{

                                $handler        .=      sprintf(    '<li class="d-flex align-items-center">

                                                                        <a  href="javascript:"

                                                                            data-post-id="%1$s"

                                                                            class="hire-icon d-flex align-items-center">

                                                                            %2$s <span>%3$s</span>

                                                                        </a>

                                                                    </li>',

                                                                    absint( $post_id ),

                                                                    $heart_icon,

                                                                    esc_attr( $string )
                                                        );
                            }
                        }

                        elseif( $is_vendor || $is_admin ){

                            $handler        .=      sprintf(    '<li class="d-flex align-items-center">

                                                                    <a  href="javascript:" class="disabled d-flex align-items-center">

                                                                        %1$s <span>%2$s</span>

                                                                    </a>

                                                                </li>',

                                                                $heart_icon,

                                                                esc_attr( $string )
                                                    );
                        }
                    }
                }

                /**
                 *  Print ?
                 *  -------
                 */
                if( $echo ){

                    print           $handler;
                }

                else{

                    return          $handler;
                }
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_WishList_Left_Widget_Filters:: get_instance();
}
