<?php
/**
 *  SDWeddingDirectory - Venue Filter & Hooks
 *  -----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Venue_Badge_Filters' ) && class_exists( 'SDWeddingDirectory_Vendor_Venue_Filters' ) ){

    /**
     *  SDWeddingDirectory - Venue Filter & Hooks
     *  -----------------------------------
     */
    class SDWeddingDirectory_Vendor_Venue_Badge_Filters extends SDWeddingDirectory_Vendor_Venue_Filters{

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
             *  Badge Filters
             *  --------------
             */
            add_filter( 'sdweddingdirectory/venue/badge-filter', [ $this, 'badge_filter' ], absint( '10' ), absint( '1' ) );

            /**
             *  Venue Post Badge
             *  ------------------
             */
            add_filter( 'sdweddingdirectory/venue/badge', [ $this, 'venue_badge' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Venue ID to return Badge wise priority ID show first
         *  ------------------------------------------------------
         *  Spotlight then Featured then Pro then Default
         *  ---------------------------------------------
         */
        public static function badge_filter( $args = [] ){

            /**
             *  Each ID Order Change with First as First Load Featured Venue after another venue post
             *  -----------------------------------------------------------------------------------------
             */
            $_badge_spotlight = $_badge_featured = $_badge_professional = $_normal_post = [];

            /**
             *  Have Post Ids ?
             *  ---------------
             */
            if( parent:: _is_array( $args ) ){

                foreach ( $args as $key ) {

                    /**
                     *  Check this venue is [ FEATURED VENUE ] ?
                     *  --------------------------------------------
                     */
                    $badge     =   get_post_meta( absint( $key ), sanitize_key( 'venue_badge'), true );

                    /**
                     *  Venue Have Badge
                     *  ------------------
                     */
                    if( $badge !== '' && $badge != '' && $badge !== absint( '0' ) ){

                        /**
                         *  1. First Load Venue ( Spotlight )
                         *  -----------------------------------
                         */
                        if( $badge  ==  esc_attr( 'spotlight' ) ){

                            /**
                             *  Have Spotlight Badge
                             *  --------------------
                             */
                            $_badge_spotlight[] = absint( $key );


                        }elseif( $badge  ==  esc_attr( 'featured' ) ){

                            /**
                             *  Have Featured Badge
                             *  -------------------
                             */
                            $_badge_featured[]  = absint( $key );

                        
                        }elseif( $badge  ==  esc_attr( 'professional' ) ){

                            /**
                             *  Have Professional Badge
                             *  -----------------------
                             */
                            $_badge_professional[] = absint( $key );
                        }

                    }else{

                        /**
                         *  Normal Venue = Without Any Badge Load
                         *  ---------------------------------------
                         */
                        $_normal_post[] = absint( $key );
                    }
                }
            }else{

                return;
            }

            /**
             *  Set Order to load venue.
             *  -------------------------
             */
            $_venue_order     =   array_merge(

                /**
                 *  1. Load First [ spotlight ] Badge Venues
                 *  ------------------------------------------
                 */
                parent:: _is_array( $_badge_spotlight )

                ?   $_badge_spotlight   :   [],

                /**
                 *  2. Load First [ featured ] Badge Venues
                 *  -----------------------------------------
                 */
                parent:: _is_array( $_badge_featured )

                ?   $_badge_featured   :   [],

                /**
                 *  3. Load First [ professional ] Badge Venues
                 *  ---------------------------------------------
                 */
                parent:: _is_array( $_badge_professional )

                ?   $_badge_professional   :   [],

                /**
                 *  4. Load Another Venues [ Without Any Badge ]
                 *  ----------------------------------------------
                 */
                parent:: _is_array( $_normal_post )

                ?   $_normal_post     :   []
            );

            /**
             *  Return : Badge wise venue IDs
             *  -------------------------------
             */
            return  $_venue_order;
        }

        /**
         *  1. Venue Badge
         *  ----------------
         */
        public static function venue_badge( $args = [] ){

            /**
             *  Make sure args not empty!
             *  -------------------------
             */
            if(  parent:: _is_array( $args )  ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( wp_parse_args( $args, [

                    'post_id'       =>      absint( '0' ),

                    'layout'        =>      absint( '1' ),

                    'handling'      =>      ''

                ] ) );

                /**
                 *  Make sure post id not emtpy!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return;
                }

                /**
                 *  Check this venue is [ FEATURED VENUE ] ?
                 *  --------------------------------------------
                 */
                $badge     =   get_post_meta( absint( $post_id ), sanitize_key( 'venue_badge'), true );

                /**
                 *  Venue Have Badge
                 *  ------------------
                 */
                if( ! empty( $badge ) ){

                    /**
                     *  Spotlight Badge
                     *  ---------------
                     */
                    if( $badge  ==  esc_attr( 'spotlight' ) ){

                        /**
                         *  Layout 1
                         *  --------
                         */
                        if( $layout  ==  absint( '1' ) ){

                            $handling       =       sprintf(   '<span class="spotlight featured">

                                                                    <i class="fa fa-bolt"></i>

                                                                    <span>%1$s</span>

                                                                </span>',

                                                                /**
                                                                 *  1. Badge Name
                                                                 *  -------------
                                                                 */
                                                                esc_attr__( 'Spotlight', 'sdweddingdirectory-venue' )
                                                    );
                        }

                        /**
                         *  Layout 2
                         *  --------
                         */
                        if( $layout  ==  absint( '2' ) ){

                            $handling       =       sprintf(   '<span class="spotlight featured d-flex justify-content-center">

                                                                    <i class="fa fa-bolt"></i>

                                                                </span>'
                                                    );
                        }
                    }

                    /**
                     *  Featured Badge
                     *  --------------
                     */
                    elseif( $badge  ==  esc_attr( 'featured' ) ){

                        /**
                         *  Layout 1
                         *  --------
                         */
                        if( $layout  ==  absint( '1' ) ){

                            $handling       =       sprintf(   '<span class="featured">

                                                                    <i class="fa fa-star"></i>

                                                                    <span>%1$s</span>

                                                                </span>',

                                                                /**
                                                                 *  1. Badge Name
                                                                 *  -------------
                                                                 */
                                                                esc_attr__( 'Featured', 'sdweddingdirectory-venue' )
                                                    );
                        }

                        /**
                         *  Layout 2
                         *  --------
                         */
                        if( $layout  ==  absint( '2' ) ){

                            $handling       =       sprintf(   '<span class="featured d-flex justify-content-center">

                                                                    <i class="fa fa-star"></i>

                                                                </span>'
                                                    );
                        }
                    }

                    /**
                     *  Pro Badge
                     *  ---------
                     */
                    elseif( $badge  ==  esc_attr( 'professional' ) ){

                        /**
                         *  Layout 1
                         *  --------
                         */
                        if( $layout  ==  absint( '1' ) ){

                            $handling       =       sprintf(   '<span class="professional featured">

                                                                    <i class="fa fa-briefcase"></i>

                                                                    <span>%1$s</span>

                                                                </span>',

                                                                /**
                                                                 *  1. Badge Name
                                                                 *  -------------
                                                                 */
                                                                esc_attr__( 'Pro', 'sdweddingdirectory-venue' )
                                                    );
                        }

                        /**
                         *  Layout 2
                         *  --------
                         */
                        if( $layout  ==  absint( '2' ) ){

                            $handling       =       sprintf(   '<span class="professional featured d-flex justify-content-center">

                                                                    <i class="fa fa-briefcase"></i>

                                                                </span>'
                                                    );
                        }
                    }
                }

                /**
                 *  Return Badge
                 *  ------------
                 */
                return          $handling;
            }
        }
        
    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Vendor_Venue_Badge_Filters:: get_instance();
}