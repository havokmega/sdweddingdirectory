<?php
/**
 *  SDWeddingDirectory - Venue
 *  --------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue' ) && class_exists( 'SDWeddingDirectory_Config' ) ){

    /**
     *  SDWeddingDirectory - Venue
     *  --------------------
     */
    class SDWeddingDirectory_Venue extends SDWeddingDirectory_Config{

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
             *  1. Venue Post Get Filter
             *  --------------------------
             */
            add_filter( 'sdweddingdirectory/venue/post', [ $this, 'sdweddingdirectory_venue_layout' ], absint( '10' ), absint( '1' ) );

            /**
             *  2. Venue Post ID to get Map Data
             *  ----------------------------------
             */
            add_filter( 'sdweddingdirectory/venue/post/map-info', [ $this, 'venue_map_data' ], absint( '10' ), absint( '1' ) );

            /**
             *  3. Post Price Tag
             *  -----------------
             */
            add_filter( 'sdweddingdirectory/venue/post/price', [ $this, 'venue_post_price' ], absint( '10' ), absint( '1' )  );

            /**
             *  4. Post Thumbnail
             *  -----------------
             */
            add_filter( 'sdweddingdirectory/venue/post/thumbnail', [ $this, 'venue_image' ], absint( '10' ), absint( '1' ) );

            /**
             *  5. Is Verify Venue ?
             *  ----------------------
             */
            add_filter( 'sdweddingdirectory/venue/post/verify-badge', [ $this, 'verify_badge' ], absint( '10' ), absint( '1' ) );

            /**
             *  6. Venue Post Type - Layout 1 - Top Section
             *  ---------------------------------------------
             */
            add_filter( 'sdweddingdirectory/venue/post/layout-1/top-section', [ $this, 'venue_badge' ], absint( '10' ), absint( '2' ) );

            /**
             *  6. Venue Post Type - Layout 1 - Top Section
             *  ---------------------------------------------
             */
            add_filter( 'sdweddingdirectory/venue/post/layout-1/top-section', [ $this, 'venue_pricing' ], absint( '20' ), absint( '2' ) );

            /**
             *  7. Venue Post Type - Layout 1 - Top Section
             *  ---------------------------------------------
             */
            add_filter( 'sdweddingdirectory/venue/post/layout-1/bottom-section', [ $this, 'venue_category_tag' ], absint( '10' ), absint( '2' ) );

            /**
             *  8. Venue Post Type - Layout 1 - Top Section
             *  ---------------------------------------------
             */
            add_filter( 'sdweddingdirectory/venue/post/layout-1/bottom-section', [ $this, 'venue_in_wishlist' ], absint( '20' ), absint( '2' ) );

            /**
             *  9. Venue Post Type - Layout 1 - Content Area
             *  ----------------------------------------------
             */
            add_filter( 'sdweddingdirectory/venue/post/layout-1/content-area', [ $this, 'venue_content_area' ], absint( '10' ), absint( '2' ) );

            /**
             *  10. Venue Post Type - Layout 1 - Location 
             *  -------------------------------------------
             */
            add_filter( 'sdweddingdirectory/venue/post/layout-1/content-area', [ $this, 'venue_location' ], absint( '20' ), absint( '2' ) );

            /**
             *  10. Venue Post Type - Layout 1 - Location 
             *  -------------------------------------------
             */
            add_filter( 'sdweddingdirectory/venue/post/layout-1/bottom-area', [ $this, 'venue_bottom_review' ], absint( '10' ), absint( '2' ) );

            /**
             *  10. Venue Post Type - Layout 1 - Location 
             *  -------------------------------------------
             */
            add_filter( 'sdweddingdirectory/venue/post/layout-1/image-section', [ $this, 'venue_image_section' ], absint( '10' ), absint( '2' ) );

            /**
             *  Map Latitude
             *  ------------
             */
            add_filter( 'sdweddingdirectory/venue/latitude', [ $this, 'venue_latitude' ], absint( '10' ), absint( '1' ) );

            /**
             *  Map Longitude
             *  -------------
             */
            add_filter( 'sdweddingdirectory/venue/longitude', [ $this, 'venue_longitude' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Venue Post - Rating Widget
         *  ----------------------------
         */
        public static function venue_bottom_review( $data = '', $args = [] ){

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

                    'post_id'       =>      absint( '0' ),

                    'layout'        =>      absint( '1' )

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return  $data;
                }

                /**
                 *  Return Badge
                 *  ------------
                 */
                return      $data   .       self:: venue_post_review( absint( $post_id ) );
            }
        }

        /**
         *  Venue Post - Category Tag Widget
         *  ----------------------------------
         */
        public static function venue_location( $data = '', $args = [] ){

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

                    'post_id'       =>      absint( '0' ),

                    'layout'        =>      absint( '1' )

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return  $data;
                }

                /**
                 *  Return Badge
                 *  ------------
                 */
                return      $data   .   self:: venue_post_location( $post_id );
                            
            }
        }

        /**
         *  Venue Post - Category Tag Widget
         *  ----------------------------------
         */
        public static function venue_content_area( $data = '', $args = [] ){

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

                    'post_id'       =>      absint( '0' ),

                    'layout'        =>      absint( '1' )

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return  $data;
                }

                /**
                 *  Return Badge
                 *  ------------
                 */
                return      $data   .

                            sprintf( '<h3><a href="%1$s">%2$s</a> %3$s</h3>', 

                                /**
                                 *  1. Post link
                                 *  ------------
                                 */
                                esc_url( get_the_permalink( absint( $post_id ) ) ),

                                /**
                                 *  2. Post Name
                                 *  ------------
                                 */
                                esc_attr( get_the_title( absint( $post_id ) ) ),

                                /**
                                 *  3. Verify Badge
                                 *  ---------------
                                 */
                                apply_filters( 'sdweddingdirectory/venue/post/verify-badge', [

                                    'post_id'       =>      absint( $post_id )

                                ] )
                            );
            }
        }

        /**
         *  Venue Post - Category Tag Widget
         *  ----------------------------------
         */
        public static function venue_category_tag( $data = '', $args = [] ){

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

                    'post_id'       =>      absint( '0' ),

                    'layout'        =>      absint( '1' )

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return  $data;
                }

                /**
                 *  Return Badge
                 *  ------------
                 */
                return      $data  .   self:: venue_post_category( absint( $post_id ) );
            }
        }

        /**
         *  Venue Post in Wishlist Widget
         *  -------------------------------
         */
        public static function venue_in_wishlist( $data = '', $args = [] ){

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

                    'post_id'       =>      absint( '0' ),

                    'layout'        =>      absint( '1' )

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ---------------------
                 */
                if( empty( $post_id ) ){

                    return  $data;
                }

                /**
                 *  Return Badge
                 *  ------------
                 */
                return      $data  .   self:: venue_post_wishlist( absint( $post_id ) );
            }
        }


        /**
         *  Venue Post badge Widget
         *  -------------------------
         */
        public static function venue_badge( $data = '', $args = [] ){

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

                    'post_id'       =>      absint( '0' ),

                    'layout'        =>      absint( '1' )

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ---------------------
                 */
                if( empty( $post_id ) ){

                    return  $data;
                }

                /**
                 *  Return Badge
                 *  ------------
                 */
                return      $data  .

                            apply_filters(  'sdweddingdirectory/venue/badge', [

                                'post_id'       =>      absint( $post_id )

                            ]  );
            }
        }

        /**
         *  Venue Post badge Widget
         *  -------------------------
         */
        public static function venue_pricing( $data = '', $args = [] ){

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

                    'post_id'       =>      absint( '0' ),

                    'layout'        =>      absint( '1' )

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ---------------------
                 */
                if( empty( $post_id ) ){

                    return  $data;
                }

                /**
                 *  Return Badge
                 *  ------------
                 */
                return      $data  .   apply_filters( 'sdweddingdirectory/venue/post/price', [

                                            'post_id'       =>   absint( $post_id )

                                        ] );
            }
        }

        /**
         *  Venue Post badge Widget
         *  -------------------------
         */
        public static function venue_image_section( $data = '', $args = [] ){

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

                    'post_id'       =>      absint( '0' ),

                    'layout'        =>      absint( '1' )

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ---------------------
                 */
                if( empty( $post_id ) ){

                    return  $data;
                }

                /**
                 *  Return Badge
                 *  ------------
                 */
                return      sprintf( '<a href="%1$s"><img class="w-100" src="%2$s" alt="%3$s"></a>', 

                                /**
                                 *  1. Link
                                 *  -------
                                 */
                                esc_url( get_the_permalink( absint( $post_id ) ) ),

                                /**
                                 *  2. Post Thumbnails
                                 *  ------------------
                                 */
                                apply_filters( 'sdweddingdirectory/venue/post/thumbnail', [

                                    'post_id'       =>  absint( $post_id )

                                ] ),

                                /**
                                 *  3. Image Alt
                                 *  ------------
                                 */
                                esc_attr( self:: venue_image_alt( absint( $post_id ) ) )
                            );
            }
        }

        /**
         *  Venue Image ALT
         *  -----------------
         */
        public static function venue_image_alt( $post_id = '' ){

            /**
             *  Venue Image Size
             *  ------------------
             */
            $media_id       =   get_post_meta( absint( $post_id ), sanitize_key( '_thumbnail_id' ), true );

            $tax_name       =   esc_attr( 'venue-type' );

            /**
             *  Get Category For this Vendor
             *  ----------------------------
             */
            $cat_id         =   apply_filters( 'sdweddingdirectory/post/term-parent', [

                                    'post_id'   =>  absint( $post_id ),

                                    'taxonomy'  =>  esc_attr( $tax_name ),

                                    '_key'      =>  absint( '0' )

                                ] );

            /**
             *  Return : Image Alt
             *  ------------------
             */
            return      parent:: _alt( [

                            'media_id'      =>      absint( $media_id ), 

                            'post_id'       =>      absint( $post_id ),

                            'start_alt'     =>      ! empty( $cat_id ) && ! empty( $tax_name )  

                                                    ?   sprintf( esc_attr__( '%1$s Venue Category', 'sdweddingdirectory-venue' ),

                                                            /**
                                                             *  1. Get Category Name
                                                             *  --------------------
                                                             */
                                                            esc_attr(  get_term( absint( $cat_id ), esc_attr( $tax_name )  )->name  )
                                                        )

                                                    :   ''
                        ] );
        }

        /**
         *  1. Load Venue Box Layout
         *  --------------------------
         */
        public static function sdweddingdirectory_venue_layout( $args = [] ){

            /**
             *  Global Var
             *  ----------
             */
            global $wp_query, $post;

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

                    'layout'            =>      absint( '1' ),

                    'post_id'           =>      absint( '0' ),

                    'unique_id'         =>      absint( '0' ),

                    'collection'        =>      '',

                    'target_class'      =>      ''

                ] ) );

                /**
                 *  If Post Id Not Empty!
                 *  ---------------------
                 */
                if( empty( $post_id ) ){

                    return;
                }

                /**
                 *  Post Query
                 *  ----------
                 */
                $query      =       new WP_Query( [

                                        'post_type'         => esc_attr( 'venue' ), 

                                        'post_status'       => esc_attr( 'publish' ),

                                        'post__in'          => array( $post_id )

                                    ] );
                /**
                 *  Have query ?
                 *  ------------
                 */
                if ( $query->have_posts() ){

                    /**
                     *  In Loop
                     *  -------
                     */
                    while ( $query->have_posts() ){  $query->the_post(); 

                        /**
                         *  Current Venue Post ID
                         *  -----------------------
                         */
                        $post_id   =   absint( get_the_ID() );

                        /**
                         *  2. Check Layout Is 1 / 2 ?
                         *  --------------------------
                         */
                        if( $layout == absint( '1' ) ){

                            /**
                             *  Collection
                             *  ----------
                             */
                            $collection   .=

                            sprintf('   <div class="sdweddingdirectory_venue %1$s" id="sdweddingdirectory_venue_%2$s">

                                            %3$s <!-- map data -->

                                            <div class="wedding-venue">

                                                <div class="img">

                                                    <div class="img-content">

                                                        %19$s <!-- Image Section -->

                                                        <div class="top">

                                                            %16$s <!-- Have Top Features Filter -->

                                                        </div>

                                                        <div class="bottom">

                                                            %17$s <!-- Have Bottom Widget -->

                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="content">

                                                    <div class="gap">

                                                        %18$s <!-- venue bottom content -->

                                                    </div>

                                                    %12$s

                                                </div>

                                            </div>

                                        </div>',

                                        /**
                                         *  1. Target Class ?
                                         *  -----------------
                                         */
                                        parent:: _is_array( $target_class )

                                        ?   apply_filters( 'sdweddingdirectory/class', $target_class )

                                        :   sanitize_html_class( $target_class ),

                                        /**
                                         *  2. Venue ID
                                         *  -------------
                                         */
                                        absint( $post_id ),

                                        /**
                                         *  3. Get Venue Map Data
                                         *  -----------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/venue/post/map-info', [

                                            /**
                                             *  1. Post ID to collection of Map Data
                                             *  ------------------------------------
                                             */
                                            'post_id'       =>      absint( $post_id )

                                        ] ),

                                        /**
                                         *  4. Venue Singular Page Link
                                         *  -----------------------------
                                         */
                                        esc_url( get_the_permalink( absint( $post_id ) ) ),

                                        /**
                                         *  5. Get Venue Thumbnails
                                         *  -------------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/venue/post/thumbnail', [

                                            'post_id'       =>  absint( $post_id )

                                        ] ),

                                        /**
                                         *  6. Venue Pricing
                                         *  ------------------
                                         */
                                        '',

                                        /**
                                         *  7. Venue Category
                                         *  -------------------
                                         */
                                        '',
                                        
                                        /**
                                         *  8. Wishlist Icon
                                         *  ----------------
                                         */
                                        '',

                                        /**
                                         *  9. Venue Post Title
                                         *  ---------------------
                                         */
                                        esc_attr( get_the_title( $post_id ) ),

                                        /**
                                         *  10. Is Verify Vendor ?
                                         *  ----------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/venue/post/verify-badge', [

                                            'post_id'       =>      absint( $post_id )

                                        ] ),

                                        /**
                                         *  11. Venue Location
                                         *  --------------------
                                         */
                                        self:: venue_post_location( absint( $post_id ) ),

                                        /**
                                         *  12. Venue Review
                                         *  ------------------
                                         */
                                        !   empty(

                                                apply_filters( 'sdweddingdirectory/venue/post/layout-1/bottom-area', '', [

                                                    'post_id'       =>      absint( $post_id ),

                                                    'layout'        =>      absint( '1' )
                                                ] )
                                            )

                                        ?   sprintf( '<div class="border-top p-3 align-items-center">%1$s</div>', 

                                                apply_filters( 'sdweddingdirectory/venue/post/layout-1/bottom-area', '', [

                                                    'post_id'       =>      absint( $post_id ),

                                                    'layout'        =>      absint( '1' )
                                                ] )
                                            )

                                        :   '',

                                        /**
                                         *  13. Venue Badge
                                         *  -----------------
                                         */
                                        '',

                                        /**
                                         *  14. Image ALT
                                         *  -------------
                                         */
                                        esc_attr( self:: venue_image_alt( absint( $post_id ) ) ),

                                        /**
                                         *  15. Have Setting Capacity
                                         *  -------------------------
                                         */
                                        self:: venue_post_seat_capacity_badge( absint( $post_id ) ),

                                        /**
                                         *  16. Venue Post Layout 1 Top Section Filter Widget
                                         *  ---------------------------------------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/venue/post/layout-1/top-section', '', [

                                            'post_id'       =>      absint( $post_id ),

                                            'layout'        =>      absint( '1' )

                                        ] ),

                                        /**
                                         *  17. Venue Post Layout 1 Bottom Section Filter Widget
                                         *  ------------------------------------------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/venue/post/layout-1/bottom-section', '', [

                                            'post_id'       =>      absint( $post_id ),

                                            'layout'        =>      absint( '1' )
                                        ] ),

                                        /**
                                         *  18. Venue Content Area
                                         *  ------------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/venue/post/layout-1/content-area', '', [

                                            'post_id'       =>      absint( $post_id ),

                                            'layout'        =>      absint( '1' )
                                        ] ),

                                        /**
                                         *  19. Venue Image Section
                                         *  -------------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/venue/post/layout-1/image-section', '', [

                                            'post_id'       =>      absint( $post_id ),

                                            'layout'        =>      absint( '1' )
                                        ] )

                            ); // sprintf ending..
                        }

                        /**
                         *  2. Check Layout Is 1 / 2 ?
                         *  --------------------------
                         */
                        if( $layout == absint( '2' ) ){

                            $collection   .=

                            sprintf('   <div class="sdweddingdirectory_venue %1$s" id="sdweddingdirectory_venue_%2$s">

                                            %3$s <!-- map data -->

                                            <div class="result-list">

                                                <div class="row">

                                                    <div class="col-lg-4 col-md-6 col-12">

                                                        <div class="img">

                                                            %13$s <!-- Have Venue badge -->

                                                            <a href="%4$s">
                                                                <img src="%5$s" alt="%17$s" class="rounded w-100">
                                                            </a>

                                                        </div>

                                                    </div>

                                                    <div class="col-lg-8 col-md-6 col-12">

                                                        <div class="content">

                                                            <div class="head">

                                                                %8$s <!-- Wishlist -->

                                                                <h3><a href="%4$s">%9$s</a> %10$s </h3>

                                                                <div class="rating">

                                                                    %11$s <!-- Venue Location -->

                                                                    %12$s <!-- Venue Reviews -->

                                                                </div>

                                                            </div>

                                                            <p>%14$s</p> <!-- Venue Content -->

                                                            <!-- bottom -->
                                                            <div class="bottom">

                                                                <div class="badge-wrap">

                                                                    <span class="price-map">

                                                                        %6$s <!-- Venue Have Pricing -->

                                                                    </span>

                                                                    %15$s <!-- Venue Have Seat Capacity -->

                                                                </div>
                                                                <!-- badge-wrap -->

                                                                <!-- Request Quote Button -->
                                                                %16$s <!-- Request Quote For Venue -->
                                                                <!-- Request Quote Button -->

                                                            </div>
                                                            <!-- bottom -->

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>',

                                        /**
                                         *  1. Target Class ?
                                         *  -----------------
                                         */
                                        parent:: _is_array( $target_class )

                                        ?   apply_filters( 'sdweddingdirectory/class', $target_class )

                                        :   sanitize_html_class( $target_class ),

                                        /**
                                         *  2. Venue ID
                                         *  -------------
                                         */
                                        absint( $post_id ),

                                        /**
                                         *  3. Get Venue Map Data
                                         *  -----------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/venue/post/map-info', [

                                            /**
                                             *  1. Post ID to collection of Map Data
                                             *  ------------------------------------
                                             */
                                            'post_id'       =>      absint( $post_id )

                                        ] ),

                                        /**
                                         *  4. Venue Singular Page Link
                                         *  -----------------------------
                                         */
                                        esc_url( get_the_permalink( absint( $post_id ) ) ),

                                        /**
                                         *  5. Get Venue Thumbnails
                                         *  -------------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/venue/post/thumbnail', [

                                            'post_id'       =>  absint( $post_id )

                                        ] ),

                                        /**
                                         *  6. Venue Pricing
                                         *  ------------------ 
                                         */
                                        apply_filters( 'sdweddingdirectory/venue/post/price', [

                                            'post_id'       =>   absint( $post_id )

                                        ] ),

                                        /**
                                         *  7. Venue Category
                                         *  -------------------
                                         */
                                        self:: venue_post_category( absint( $post_id ) ),
                                        
                                        /**
                                         *  8. Wishlist Icon
                                         *  ----------------
                                         */
                                        self:: venue_post_wishlist( absint( $post_id ) ),

                                        /**
                                         *  9. Venue Post Title
                                         *  ---------------------
                                         */
                                        esc_attr( get_the_title( $post_id ) ),

                                        /**
                                         *  10. Is Verify Vendor ?
                                         *  ----------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/venue/post/verify-badge', [

                                            'post_id'       =>      absint( $post_id )

                                        ] ),

                                        /**
                                         *  11. Venue Location
                                         *  --------------------
                                         */
                                        self:: venue_post_location( absint( $post_id ) ),

                                        /**
                                         *  12. Venue Review
                                         *  ------------------
                                         */
                                        self:: venue_post_review( absint( $post_id ) ),

                                        /**
                                         *  13. Venue Badge
                                         *  -----------------
                                         */
                                        apply_filters(  'sdweddingdirectory/venue/badge', [

                                            'post_id'       =>      absint( $post_id )

                                        ]  ),

                                        /**
                                         *  14. Venue Description
                                         *  -----------------------
                                         */
                                        self:: venue_post_expert_content( absint( $post_id ) ),

                                        /**
                                         *  15. Venue Seat Capacity
                                         *  -------------------------
                                         */
                                        self:: venue_post_seat_capacity( absint( $post_id ) ),

                                        /**
                                         *  16. Venue Request Quote
                                         *  -------------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/venue/post/request-quote', '', [

                                            'post_id'       =>  absint( $post_id ),

                                            'button_style'  =>  explode( ' ', 'btn btn-outline-primary btn-rounded' ),
                                        ] ),

                                        /**
                                         *  17. Image ALT
                                         *  -------------
                                         */
                                        esc_attr( self:: venue_image_alt( absint( $post_id ) ) )

                            ); // sprintf ending..
                        }

                        /**
                         *  Venues Result Layout
                         *  --------------------
                         */
                        if( $layout == absint( '8' ) ){

                            $location_terms = SDWeddingDirectory_Taxonomy:: get_location_taxonomy(

                                                absint( $post_id ),

                                                esc_attr( 'venue-location' )
                                            );

                            $location_text = parent:: _is_array( $location_terms )

                                            ? implode( ', ', array_map( 'sanitize_text_field', $location_terms ) )

                                            : '';

                            $description_text = self:: venue_post_expert_content( absint( $post_id ) );

                            $price_text = self:: venue_post_price_text( absint( $post_id ) );

                            $capacity_text = self:: venue_post_capacity_text( absint( $post_id ) );

                            $collection   .=

                            sprintf( '<div class="sdweddingdirectory_venue %1$s" id="sdweddingdirectory_venue_%2$s">

                                            %3$s

                                            <div class="sd-vendor-result-card mb-4">

                                                <div class="row g-0">

                                                    <div class="col-md-4">

                                                        <a href="%4$s">
                                                            <img src="%5$s" class="img-fluid rounded-start" alt="%11$s">
                                                        </a>

                                                    </div>

                                                    <div class="col-md-8">

                                                        <div class="card-body p-3">

                                                            <h5 class="card-title mb-1">
                                                                <a href="%4$s">%6$s</a>
                                                            </h5>

                                                            %7$s

                                                            %8$s

                                                            %9$s

                                                            %10$s

                                                            <a href="%4$s" class="btn btn-sm btn-outline-dark">%12$s</a>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>',

                                        parent:: _is_array( $target_class )

                                        ?   apply_filters( 'sdweddingdirectory/class', $target_class )

                                        :   sanitize_html_class( $target_class ),

                                        absint( $post_id ),

                                        apply_filters( 'sdweddingdirectory/venue/post/map-info', [

                                            'post_id'       =>      absint( $post_id )
                                        ] ),

                                        esc_url( get_the_permalink( absint( $post_id ) ) ),

                                        apply_filters( 'sdweddingdirectory/venue/post/thumbnail', [

                                            'post_id'       =>  absint( $post_id )
                                        ] ),

                                        esc_html( get_the_title( absint( $post_id ) ) ),

                                        parent:: _have_data( $location_text )

                                        ?   sprintf( '<p class="text-muted small mb-2"><i class="fa fa-map-marker me-1"></i>%1$s</p>', esc_html( $location_text ) )

                                        :   '',

                                        parent:: _have_data( $description_text )

                                        ?   sprintf( '<p class="card-text small mb-2">%1$s</p>', esc_html( $description_text ) )

                                        :   '',

                                        parent:: _have_data( $price_text )

                                        ?   sprintf( '<p class="fw-bold mb-2">%1$s</p>', esc_html( $price_text ) )

                                        :   '',

                                        parent:: _have_data( $capacity_text )

                                        ?   sprintf( '<p class="small mb-2">%1$s</p>', esc_html( $capacity_text ) )

                                        :   '',

                                        esc_attr( self:: venue_image_alt( absint( $post_id ) ) ),

                                        esc_html__( 'View Venue', 'sdweddingdirectory-venue' )
                            );
                        }

                        /**
                         *  Couple Wishlist Purpose ONLY
                         *  ----------------------------
                         *  Wishlist Layout 3 for lising box different look
                         *  -----------------------------------------------
                         */
                        
                        $_condition_1   =  parent:: is_couple();

                        $_condition_2   =  parent:: dashboard_page_set( esc_attr( 'vendor-manager' ) );

                        $_condition_3   =  $_condition_1 && $_condition_2;

                        /**
                         *  Couple Wishlist Venue : Layout 3
                         *  ----------------------------------
                         */
                        if( $layout == absint( '3' ) ){

                            $collection   .=

                            sprintf('   <div class="sdweddingdirectory_venue mb-4 %1$s" id="sdweddingdirectory_venue_%2$s">

                                            <div class="wedding-venue" data-wishlist-id="%3$s">

                                                <div class="img">

                                                    <a href="%4$s"><img src="%5$s" alt="%24$s" class="w-100" /></a>

                                                    <div class="img-content">
                                                    
                                                        <div class="top text-end">

                                                            %8$s <!-- Wishlist -->

                                                        </div>

                                                        <div class="bottom">

                                                            %12$s <!-- Venue Reviews -->

                                                        </div>

                                                    </div>

                                                </div>
                                                
                                                <div class="content">

                                                    <div class="gap">

                                                        <h4><a href="%4$s">%9$s</a> %10$s </h4>

                                                        <div class="mb-2">

                                                            %11$s <!-- Venue Location -->
                                                            
                                                        </div>

                                                        %17$s <!-- wishlist choose -->

                                                        <div class="d-flex align-items-center row g-0 mt-3">

                                                            <div class="col-7">

                                                                <label>%22$s</label>

                                                                <div class="mt-1">

                                                                    <div class="sdweddingdirectory_wishlist_review" data-review="%23$s"></div>

                                                                </div>

                                                            </div>

                                                            <div class="col-5 text-end">

                                                                <div><label>Price</label></div>

                                                                <div class="d-flex align-items-center mt-1">

                                                                    <span class="me-3">%25$s</span>

                                                                    <input autocomplete="off" type="text" class="form-control price-wedding wishlist-estimate-price" placeholder="0" value="%21$s">
                                                                </div>

                                                            </div>

                                                        </div>

                                                        <div class="mt-3">

                                                            <label>%18$s</label>

                                                            <textarea class="form-control wishlist-note" placeholder="%19$s" rows="3">%20$s</textarea>

                                                        </div>

                                                    </div>

                                                    <div class="bottom-footer d-none">

                                                        <span><a href="javascript:"><i class="fa fa-phone"></i> Phone number</a></span>

                                                        <span><a href="javascript:" data-bs-toggle="modal" data-bs-target="#request_quote"><i class="fa fa-envelope-o"></i> Contact</a></span>

                                                    </div>

                                                </div>                                                
                                            </div>

                                        </div>',

                                        /**
                                         *  1. Target Class ?
                                         *  -----------------
                                         */
                                        parent:: _is_array( $target_class )

                                        ?   apply_filters( 'sdweddingdirectory/class', $target_class )

                                        :   sanitize_html_class( $target_class ),

                                        /**
                                         *  2. Venue ID
                                         *  -------------
                                         */
                                        absint( $post_id ),

                                        /**
                                         *  3. Get Venue Map Data
                                         *  -----------------------
                                         */
                                        absint( $unique_id ),

                                        /**
                                         *  4. Venue Singular Page Link
                                         *  -----------------------------
                                         */
                                        esc_url( get_the_permalink( absint( $post_id ) ) ),

                                        /**
                                         *  5. Get Venue Thumbnails
                                         *  -------------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/venue/post/thumbnail', [

                                            'post_id'       =>  absint( $post_id )

                                        ] ),

                                        /**
                                         *  6. Venue Pricing
                                         *  ------------------ 
                                         */
                                        apply_filters( 'sdweddingdirectory/venue/post/price', [

                                            'post_id'       =>   absint( $post_id )

                                        ] ),

                                        /**
                                         *  7. Venue Category
                                         *  -------------------
                                         */
                                        self:: venue_post_category( absint( $post_id ) ),
                                        
                                        /**
                                         *  8. Wishlist Icon
                                         *  ----------------
                                         */
                                        self:: venue_post_wishlist( absint( $post_id ) ),

                                        /**
                                         *  9. Venue Post Title
                                         *  ---------------------
                                         */
                                        esc_attr( get_the_title( $post_id ) ),

                                        /**
                                         *  10. Is Verify Vendor ?
                                         *  ----------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/venue/post/verify-badge', [

                                            'post_id'       =>      absint( $post_id )

                                        ] ),

                                        /**
                                         *  11. Venue Location
                                         *  --------------------
                                         */
                                        self:: venue_post_location( absint( $post_id ) ),

                                        /**
                                         *  12. Venue Review
                                         *  ------------------
                                         */
                                        self:: venue_post_review( absint( $post_id ) ),

                                        /**
                                         *  13. Venue Badge
                                         *  -----------------
                                         */
                                        apply_filters(  'sdweddingdirectory/venue/badge', [

                                            'post_id'       =>      absint( $post_id )

                                        ]  ),

                                        /**
                                         *  14. Venue Description
                                         *  -----------------------
                                         */
                                        self:: venue_post_expert_content( absint( $post_id ) ),

                                        /**
                                         *  15. Venue Seat Capacity
                                         *  -------------------------
                                         */
                                        self:: venue_post_seat_capacity( absint( $post_id ) ),

                                        /**
                                         *  16. Venue Request Quote
                                         *  -------------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/venue/post/request-quote', '', [

                                            'post_id'       =>  absint( $post_id ),

                                            'button_style'  =>  explode( ' ', 'btn btn-outline-primary btn-rounded' ),
                                        ] ),

                                        /**
                                         *  17. Wishlist Select Option for Choose
                                         *  -------------------------------------
                                         */
                                        sprintf(   '<select 

                                                        class="form-control couple-hire-vendor" 

                                                        name="couple-hire-vendor">

                                                        %1$s

                                                    </select>',

                                                /**
                                                 *  Display Category Dropdown
                                                 *  -------------------------
                                                 */
                                                SDWeddingDirectory_Taxonomy:: create_select_option(

                                                    /**
                                                     *  Options list
                                                     *  ------------
                                                     */
                                                    apply_filters( 'sdweddingdirectory_wishlist_choose_dropdown', [] ),

                                                    /**
                                                     *  First Value
                                                     *  -----------
                                                     */
                                                    array( '0' => esc_attr__( 'Evaluating', 'sdweddingdirectory-venue' ) ),

                                                    /**
                                                     *  3. Default Select
                                                     *  -----------------
                                                     */
                                                    class_exists( 'SDWeddingDirectory_WishList_Database' )
                                                    
                                                    &&

                                                    parent:: _have_data(

                                                        SDWeddingDirectory_WishList_Database:: wishlist_unique_id_value(

                                                            absint( $unique_id ), 

                                                            esc_attr( 'wishlist_hire_vendor' )
                                                        )
                                                    )

                                                    ?   esc_attr(

                                                            SDWeddingDirectory_WishList_Database:: wishlist_unique_id_value( 

                                                                absint( $unique_id ), 

                                                                esc_attr( 'wishlist_hire_vendor' )
                                                            )
                                                        )

                                                    :   esc_attr( 'evaluating' ),

                                                    /**
                                                     *  Print ?
                                                     *  -------
                                                     */
                                                    false
                                                ),

                                                /**
                                                 *  2. Select Option Placeholder
                                                 *  ----------------------------
                                                 */
                                                esc_attr__( 'Evaluating', 'sdweddingdirectory-venue' )
                                        ),

                                        /**
                                         *  18. Translation Ready String
                                         *  ----------------------------
                                         */
                                        esc_attr__( 'Notes', 'sdweddingdirectory-venue' ),

                                        /**
                                         *  19. Translation Ready String
                                         *  ----------------------------
                                         */
                                        esc_attr__( 'Add Note For this vendor', 'sdweddingdirectory-venue' ),

                                        /**
                                         *  20. Have Notes for this vendor ?
                                         *  --------------------------------
                                         */
                                        class_exists( 'SDWeddingDirectory_WishList_Database' )

                                        ?   SDWeddingDirectory_WishList_Database:: couple_wishlist_note( absint( $unique_id ) )

                                        :   '',

                                        /**
                                         *  21. Have Estimate Price ?
                                         *  -------------------------
                                         */
                                        class_exists( 'SDWeddingDirectory_WishList_Database' )

                                        ?   SDWeddingDirectory_WishList_Database:: couple_estimate_price( absint( $unique_id ) )

                                        :   absint( '0' ),

                                        /** 
                                         *  22. Translation Ready String
                                         *  ----------------------------
                                         */
                                        class_exists( 'SDWeddingDirectory_WishList_Database' )

                                        ?   SDWeddingDirectory_WishList_Database:: overall_fit_string( absint( $unique_id ) )

                                        :   esc_attr__( 'Overall fit', 'sdweddingdirectory-venue' ),

                                        /**
                                         *  23. Have Rating ?
                                         *  -----------------
                                         */
                                        class_exists( 'SDWeddingDirectory_WishList_Database' )

                                        ?   SDWeddingDirectory_WishList_Database:: couple_fit_venue_rating( absint( $unique_id ) )

                                        :   absint( '0' ),

                                        /**
                                         *  24. Image ALT
                                         *  -------------
                                         */
                                        esc_attr( self:: venue_image_alt( absint( $post_id ) ) ),

                                        /**
                                         *  25. Currency Symbol Update
                                         *  --------------------------
                                         */
                                        function_exists( 'sdweddingdirectory_currenty' ) 

                                        ?   esc_attr( sdweddingdirectory_currenty() )

                                        :   esc_attr( '$' )

                            ); // sprintf ending..
                        }

                        /**
                         *  Hire Vendor Layout in Couple Wishlist Page
                         *  ------------------------------------------
                         */
                        if( $layout == absint( '4' ) && $_condition_3 ){

                            $collection   .=

                            sprintf('   <div class="sdweddingdirectory_venue mb-3 %1$s" id="sdweddingdirectory_venue_%2$s">

                                            %3$s <!-- map data -->

                                            <div class="wedding-venue border">

                                                <div class="img">

                                                    <a href="%4$s"><img src="%5$s" alt="%6$s"></a> <!-- Venue Image -->

                                                </div>

                                                <div class="content text-center p-4">

                                                    <a href="%4$s#section_review" target="_blank">%7$s</a>

                                                </div>
                                            </div>

                                        </div>',

                                        /**
                                         *  1. Target Class ?
                                         *  -----------------
                                         */
                                        parent:: _is_array( $target_class )

                                        ?   apply_filters( 'sdweddingdirectory/class', $target_class )

                                        :   sanitize_html_class( $target_class ),

                                        /**
                                         *  2. Venue ID
                                         *  -------------
                                         */
                                        absint( $post_id ),

                                        /**
                                         *  3. Get Venue Map Data
                                         *  -----------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/venue/post/map-info', [

                                            /**
                                             *  1. Post ID to collection of Map Data
                                             *  ------------------------------------
                                             */
                                            'post_id'       =>      absint( $post_id )

                                        ] ),

                                        /**
                                         *  4. Venue Singular Page Link
                                         *  -----------------------------
                                         */
                                        esc_url( get_the_permalink( absint( $post_id ) ) ),

                                        /**
                                         *  5. Get Venue Thumbnails
                                         *  -------------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/venue/post/thumbnail', [

                                            'post_id'       =>  absint( $post_id )

                                        ] ),

                                        /**
                                         *  6. Image ALT
                                         *  -------------
                                         */
                                        esc_attr( self:: venue_image_alt( absint( $post_id ) ) ),

                                        /**
                                         *  7. Translation Ready String
                                         *  ---------------------------
                                         */
                                        esc_attr__( 'Write a Review', 'sdweddingdirectory-venue' )

                            ); // sprintf ending..
                        }

                        /**
                         *  Get only Map Data
                         *  -----------------
                         */
                        if( $layout == absint( '5' ) ){

                            $collection   .=

                            sprintf( '<div class="sdweddingdirectory_venue" id="sdweddingdirectory_venue_%1$s">%2$s</div>',

                                /**
                                 *  1. Post ID
                                 *  ----------
                                 */
                                absint( $post_id ),

                                /**
                                 *  2. Map Data
                                 *  -----------
                                 */
                                apply_filters( 'sdweddingdirectory/venue/post/map-info', [

                                    /**
                                     *  1. Post ID to collection of Map Data
                                     *  ------------------------------------
                                     */
                                    'post_id'       =>      absint( $post_id )

                                ] )
                            );
                        }

                        /**
                         *  Hire Vendor Layout in Couple Wishlist Page
                         *  ------------------------------------------
                         */
                        if( $layout == absint( '6' ) ){

                            $collection   .=

                            sprintf('   <div class="sdweddingdirectory_venue mb-3 %1$s" id="sdweddingdirectory_venue_%2$s">

                                            <div class="wedding-venue border">

                                                <div class="img">

                                                    <a href="%4$s"><img src="%5$s" alt="%6$s"></a> <!-- Venue Image -->

                                                </div>

                                                <div class="p-3">

                                                    <h3><a href="%4$s">%7$s</a></h3>

                                                </div>
                                            </div>

                                        </div>',

                                        /**
                                         *  1. Target Class ?
                                         *  -----------------
                                         */
                                        parent:: _is_array( $target_class )

                                        ?   apply_filters( 'sdweddingdirectory/class', $target_class )

                                        :   sanitize_html_class( $target_class ),

                                        /**
                                         *  2. Venue ID
                                         *  -------------
                                         */
                                        absint( $post_id ),

                                        /**
                                         *  3. Get Venue Map Data
                                         *  -----------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/venue/post/map-info', [

                                            /**
                                             *  1. Post ID to collection of Map Data
                                             *  ------------------------------------
                                             */
                                            'post_id'       =>      absint( $post_id )

                                        ] ),

                                        /**
                                         *  4. Venue Singular Page Link
                                         *  -----------------------------
                                         */
                                        esc_url( get_the_permalink( absint( $post_id ) ) ),

                                        /**
                                         *  5. Get Venue Thumbnails
                                         *  -------------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/venue/post/thumbnail', [

                                            'post_id'       =>  absint( $post_id )

                                        ] ),

                                        /**
                                         *  6. Image ALT
                                         *  -------------
                                         */
                                        esc_attr( self:: venue_image_alt( absint( $post_id ) ) ),

                                        /**
                                         *  7. Translation Ready String
                                         *  ---------------------------
                                         */
                                        esc_attr( get_the_title( absint( $post_id ) ) )

                            ); // sprintf ending..
                        }

                        /**
                         *  7. Layout 7
                         *  -----------
                         */
                        if( $layout == absint( '7' ) ){

                            /**
                             *  Random ID
                             *  ---------
                             */
                            $rand_id            =       esc_attr( parent:: _rand() );

                            ob_start();

                            /**
                             *  List of slider tab icon
                             *  -----------------------
                             */
                            do_action( 'sdweddingdirectory/venue/singular/left-side/widget', array(

                                'layout'    =>  absint( '3' ),

                                'post_id'   =>  absint( $post_id ),

                            ) );

                            $tab   =   ob_get_contents();

                            ob_end_clean();


                            $collection         .=

                            sprintf('   <div class="sdweddingdirectory_venue sdweddingdirectory-venue-more-info %1$s" id="sdweddingdirectory_venue_%2$s">

                                            %3$s <!-- map data -->

                                            <div class="result-list">

                                                <div class="row">

                                                    <div class="col-lg-4 col-md-6 col-12">

                                                        <div class="img">

                                                            %13$s <!-- Have Venue badge -->

                                                            <a href="%4$s">
                                                                <img src="%5$s" alt="%17$s" class="rounded w-100">
                                                            </a>

                                                        </div>

                                                    </div>

                                                    <div class="col-lg-8 col-md-6 col-12">

                                                        <div class="content">

                                                            <div class="head">

                                                                <div class="">

                                                                    <h3><a href="%4$s">%9$s</a> %10$s </h3>

                                                                    <div class="venue-location">

                                                                        %11$s <!-- Venue Location -->

                                                                    </div>

                                                                </div>

                                                                %8$s <!-- Wishlist -->

                                                            </div>

                                                            <div class="rating accordion-button p-0 bg-white border-0 pe-2 collapsed" data-bs-toggle="collapse" data-bs-target=".%19$s" aria-expanded="false" aria-controls="%19$s">

                                                                %12$s <!-- Venue Reviews -->

                                                            </div>


                                                            <p>%14$s</p> <!-- Venue Content -->

                                                            <!-- bottom -->
                                                            <div class="bottom">

                                                                <div class="badge-wrap">

                                                                    <span class="price-map">

                                                                        %6$s <!-- Venue Have Pricing -->

                                                                    </span>

                                                                    %15$s <!-- Venue Have Seat Capacity -->

                                                                </div>
                                                                <!-- badge-wrap -->

                                                                <!-- Request Quote Button -->
                                                                %16$s <!-- Request Quote For Venue -->
                                                                <!-- Request Quote Button -->

                                                            </div>
                                                            <!-- bottom -->

                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="row mt-3">

                                                  <div class="col-12">

                                                    <div class="collapse %19$s" id="%19$s">

                                                        <div class="card card-body">%18$s</div>

                                                    </div>

                                                  </div>

                                                </div>


                                            </div>

                                        </div>',

                                        /**
                                         *  1. Target Class ?
                                         *  -----------------
                                         */
                                        parent:: _is_array( $target_class )

                                        ?   apply_filters( 'sdweddingdirectory/class', $target_class )

                                        :   sanitize_html_class( $target_class ),

                                        /**
                                         *  2. Venue ID
                                         *  -------------
                                         */
                                        absint( $post_id ),

                                        /**
                                         *  3. Get Venue Map Data
                                         *  -----------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/venue/post/map-info', [

                                            /**
                                             *  1. Post ID to collection of Map Data
                                             *  ------------------------------------
                                             */
                                            'post_id'       =>      absint( $post_id )

                                        ] ),

                                        /**
                                         *  4. Venue Singular Page Link
                                         *  -----------------------------
                                         */
                                        esc_url( get_the_permalink( absint( $post_id ) ) ),

                                        /**
                                         *  5. Get Venue Thumbnails
                                         *  -------------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/venue/post/thumbnail', [

                                            'post_id'       =>  absint( $post_id )

                                        ] ),

                                        /**
                                         *  6. Venue Pricing
                                         *  ------------------ 
                                         */
                                        apply_filters( 'sdweddingdirectory/venue/post/price', [

                                            'post_id'       =>   absint( $post_id )

                                        ] ),

                                        /**
                                         *  7. Venue Category
                                         *  -------------------
                                         */
                                        self:: venue_post_category( absint( $post_id ) ),
                                        
                                        /**
                                         *  8. Wishlist Icon
                                         *  ----------------
                                         */
                                        self:: venue_post_wishlist( absint( $post_id ) ),

                                        /**
                                         *  9. Venue Post Title
                                         *  ---------------------
                                         */
                                        esc_attr( get_the_title( $post_id ) ),

                                        /**
                                         *  10. Is Verify Vendor ?
                                         *  ----------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/venue/post/verify-badge', [

                                            'post_id'       =>      absint( $post_id )

                                        ] ),

                                        /**
                                         *  11. Venue Location
                                         *  --------------------
                                         */
                                        self:: venue_post_location( absint( $post_id ) ),

                                        /**
                                         *  12. Venue Review
                                         *  ------------------
                                         */
                                        self:: venue_post_review( absint( $post_id ) ),

                                        /**
                                         *  13. Venue Badge
                                         *  -----------------
                                         */
                                        apply_filters(  'sdweddingdirectory/venue/badge', [

                                            'post_id'       =>      absint( $post_id )

                                        ]  ),

                                        /**
                                         *  14. Venue Description
                                         *  -----------------------
                                         */
                                        self:: venue_post_expert_content( absint( $post_id ) ),

                                        /**
                                         *  15. Venue Seat Capacity
                                         *  -------------------------
                                         */
                                        self:: venue_post_seat_capacity( absint( $post_id ) ),

                                        /**
                                         *  16. Venue Request Quote
                                         *  -------------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/venue/post/request-quote', '', [

                                            'post_id'       =>  absint( $post_id ),

                                            'button_style'  =>  explode( ' ', 'btn btn-outline-primary btn-rounded' ),
                                        ] ),

                                        /**
                                         *  17. Image ALT
                                         *  -------------
                                         */
                                        esc_attr( self:: venue_image_alt( absint( $post_id ) ) ),

                                        /**
                                         *  18. Rating Information 
                                         *  ----------------------
                                         */
                                        do_shortcode( sprintf( '[sdweddingdirectory_tabs layout="3"]%1$s[/sdweddingdirectory_tabs]', $tab  ) ),

                                        /**
                                         *  19. ID
                                         *  ------
                                         */
                                        $rand_id

                            ); // sprintf ending..
                        }
                    }

                    /**
                     *  Reset Query
                     *  -----------
                     */
                    if( isset( $query ) ){

                        wp_reset_postdata();
                    }
                }

                /**
                 *  Return Venue box layout
                 *  -------------------------
                 */
                return      $collection;
            }
        }

        /**
         *  Venue Featured Image
         *  ----------------------
         */
        public static function venue_image( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if(  parent:: _is_array( $args )  ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( wp_parse_args( $args, [

                    'post_id'           =>      absint( '0' ),

                    'placeholder'       =>      esc_url( parent:: placeholder( 'venue-post' ) ),

                    'image_size'        =>      esc_attr( 'sdweddingdirectory_img_600x470' )

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){
                    
                    return;
                }

                /**
                 *  Thumbnails
                 *  ----------
                 */
                $_venue_image         =   apply_filters( 'sdweddingdirectory/media-data', [

                                                'media_id'      =>      get_post_meta( $post_id, sanitize_key( '_thumbnail_id' ), true ),

                                                'image_size'    =>      $image_size

                                            ] );

                /**
                 *  5. Get Venue Thumbnails
                 *  -------------------------
                 */
                if( ! empty( $_venue_image ) ){

                    /**
                     *  Return Thumbnails
                     *  -----------------
                     */
                    return      esc_url( $_venue_image );
                }

                /**
                 *  Return : Location-based placeholder or default
                 *  -----------------------------------------------
                 */
                else{
                    $location_terms = wp_get_post_terms( $post_id, 'venue-location' );

                    if( ! is_wp_error( $location_terms ) && ! empty( $location_terms ) ){
                        foreach( $location_terms as $loc_term ){
                            $loc_file = get_template_directory() . '/assets/images/locations/' . sanitize_file_name( $loc_term->slug ) . '.jpg';

                            if( file_exists( $loc_file ) ){
                                return esc_url( get_template_directory_uri() . '/assets/images/locations/' . sanitize_file_name( $loc_term->slug ) . '.jpg' );
                            }
                        }
                    }

                    // Fallback to san-diego.jpg if no location match
                    $default_loc = get_template_directory() . '/assets/images/locations/san-diego.jpg';

                    if( file_exists( $default_loc ) ){
                        return esc_url( get_template_directory_uri() . '/assets/images/locations/san-diego.jpg' );
                    }

                    return      esc_url( $placeholder );
                }
            }
        }

        /**
         *  Venue Latitude
         *  ----------------
         */
        public static function venue_latitude( $args = [] ){

            /**
             *  Is Array ?
             *  ----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( wp_parse_args( $args, [

                    'post_id'       =>      absint( '0' ),

                    'default'       =>      sdweddingdirectory_option( 'sdweddingdirectory_latitude' ),

                    'meta_key'      =>      sanitize_key( 'venue_latitude' )

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return      $default;
                }

                /**
                 *  Have Value ?
                 *  ------------
                 */
                $value     =      get_post_meta( $post_id, $meta_key, true );

                /**
                 *  Make sure value not empty!
                 *  --------------------------
                 */
                if( empty( $value ) ){

                    return      $default;
                }

                /**
                 *  Return Meta Value
                 *  -----------------
                 */
                return          esc_attr( $value );
            }
        }

        /**
         *  Venue Latitude
         *  ----------------
         */
        public static function venue_longitude( $args = [] ){

            /**
             *  Is Array ?
             *  ----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( wp_parse_args( $args, [

                    'post_id'       =>      absint( '0' ),

                    'default'       =>      sdweddingdirectory_option( 'sdweddingdirectory_longitude' ),

                    'meta_key'      =>      sanitize_key( 'venue_longitude' )

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return      $default;
                }

                /**
                 *  Have Value ?
                 *  ------------
                 */
                $value     =      get_post_meta( $post_id, $meta_key, true );

                /**
                 *  Make sure value not empty!
                 *  --------------------------
                 */
                if( empty( $value ) ){

                    return      $default;
                }

                /**
                 *  Return Meta Value
                 *  -----------------
                 */
                return          esc_attr( $value );
            }
        }

        /**
         *  Map Data
         *  --------
         */
        public static function venue_map_data( $args = [] ){

            /**
             *  Is Array ?
             *  ----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( wp_parse_args( $args, [

                    'post_id'       =>      absint( '0' )

                ] ) );

                /**
                 *  Have Map / Post ID not Empty!
                 *  -----------------------------
                 */
                if( ! parent:: sdweddingdirectory_have_map() || empty( $post_id ) ){

                    return;
                }

                /**
                 *  Return : Map Data
                 *  -----------------
                 */
                return

                sprintf('<div class="d-none">

                            <input autocomplete="off" type="hidden" class="venue_latitude"           value="%1$s" />

                            <input autocomplete="off" type="hidden" class="venue_longitude"          value="%2$s" />

                            <input autocomplete="off" type="hidden" class="venue_image"              value="%3$s" />

                            <input autocomplete="off" type="hidden" class="venue_title"              value="%4$s" />

                            <input autocomplete="off" type="hidden" class="venue_address"            value="%5$s" />

                            <input autocomplete="off" type="hidden" class="venue_single_link"        value="%6$s" />

                            <input autocomplete="off" type="hidden" class="venue_category_icon"      value="%7$s" />

                            <input autocomplete="off" type="hidden" class="venue_category_marker"    value="%8$s" />

                            <input autocomplete="off" type="hidden" class="get_popup_data"             value="%9$s" />

                        </div>',

                        // <input autocomplete="off" type="hidden" value="%10$s" class="venue_review_count" />
                        // <input autocomplete="off" type="hidden" value="%11$s" class="venue_review_average" />

                    /**
                     *  1. Map : Venue Latitude
                     *  -------------------------
                     */
                    apply_filters( 'sdweddingdirectory/venue/latitude', [

                        'post_id'       =>      $post_id

                    ] ),

                    /**
                     *  2. Map : Venue Longitude
                     *  --------------------------
                     */
                    apply_filters( 'sdweddingdirectory/venue/longitude', [

                        'post_id'       =>      $post_id

                    ] ),

                    /**
                     *  3. Map : Venue Thumbnails
                     *  ---------------------------
                     */
                    apply_filters( 'sdweddingdirectory/venue/post/thumbnail', [

                        'post_id'       =>  absint( $post_id )

                    ] ),

                    /**
                     *  4. Map : Venue Title
                     *  ----------------------
                     */
                    esc_attr( get_the_title( absint( $post_id ) ) ),

                    /**
                     *  5. Map : Venue Address
                     *  ------------------------
                     */
                    !   empty(  get_post_meta( absint( $post_id ), sanitize_key('venue_address'), true )  )

                    ?   get_post_meta( absint( $post_id ), sanitize_key('venue_address'), true )

                    :   '',

                    /**
                     *  6. Map : Venue Singular Page Link
                     *  -----------------------------------
                     */
                    esc_url( get_the_permalink( absint( $post_id ) ) ),

                    /**
                     *  7. Map : Default Map Icon
                     *  -------------------------
                     */
                    esc_attr( SDWeddingDirectory_Taxonomy:: get_taxonomy_icon( 

                        /**
                         *  1. Post ID
                         *  ----------
                         */
                        absint( $post_id ),

                        /**
                         *  2. Taxonomy
                         *  -----------
                         */
                        esc_attr( 'venue-type' )
                    ) ),

                    /**
                     *  8. Map : Venue Category Marker Image
                     *  --------------------------------------
                     */
                    apply_filters( 'sdweddingdirectory/term/marker', [

                        'post_id'   =>   absint( $post_id ),

                    ] ),

                    /**
                     *  9. Venue Post ID to Get Vendor ( Author ) ID
                     *  -----------------------------------------------
                     */
                    esc_html(

                        apply_filters( 'sdweddingdirectory/venue/post/request-quote', '', [

                            'post_id'       =>  absint( $post_id ),

                            'button_style'  =>  explode( ' ', 'btn btn-outline-primary btn-rounded' ),
                        ] )
                    ),

                    /**
                     *  10. Get Average Rating
                     *  ----------------------
                     */
                    // absint( SDWeddingDirectory_Reviews_Database:: venue_review_overview( array(

                    //     'venue_id'  =>  absint( $post_id ),

                    //     'meta_key'    =>  sanitize_key( 'counter' )

                    // ) ) ),

                    /**
                     *  11. Get Review Counter
                     *  ----------------------
                     */
                    // SDWeddingDirectory_Reviews_Database:: venue_review_overview( array(

                    //     'venue_id'  =>  absint( $post_id ),

                    //     'meta_key'    =>  sanitize_key( 'average_rating' )

                    // ) )

                );
            }
        }

        /**
         *  Venue Have Seat Capacity
         *  --------------------------
         */
        public static function venue_post_seat_capacity_badge( $post_id = '' ){

            if( empty( $post_id ) ){
                return;
            }

            global $post, $wp_query;

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

                                                                        'post_id'       =>      absint( $post_id ),

                                                                        'taxonomy'      =>      esc_attr( 'venue-type' )

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
             *  Both Condition check to load seat capacity data
             *  -----------------------------------------------
             */
            if( $_condition_1 && $_condition_2 ){

                return  

                sprintf( '<span class="seating-capacity"><i class="fa fa-users"></i><span> %1$s - %2$s %3$s</span></span>',

                    /**
                     *  1. Min Seat
                     *  -----------
                     */
                    absint( '1' ),

                    /**
                     *  2. Max Seat
                     *  -----------
                     */
                    absint( $_post_have_seat_data ),

                    /**
                     *  3. Translation Ready String
                     *  ---------------------------
                     */
                    esc_attr__( 'Guests', 'sdweddingdirectory-venue' )
                );
            }
        }

        /**
         *  Venue Have Seat Capacity
         *  --------------------------
         */
        public static function venue_post_seat_capacity( $post_id = '' ){

            if( empty( $post_id ) ){
                return;
            }

            global $post, $wp_query;

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

                                                                        'post_id'       =>      absint( $post_id ),

                                                                        'taxonomy'      =>      esc_attr( 'venue-type' )

                                                                    ] )
                                        ] );

            /**
             *  1. Post Have Seat Capacity >= 0 ?
             *  ---------------------------------
             */
            $_condition_1   =   parent:: _have_data( $_post_have_seat_data );

            /**
             *  2. This Category Have Seat Capacity ?
             *  -------------------------------------
             */
            $_condition_2   =   $_have_seat_capacity == true || $_have_seat_capacity == 'true' || $_have_seat_capacity == absint( '1' );

            /**
             *  Both Condition check to load seat capacity data
             *  -----------------------------------------------
             */
            if( $_condition_1 && $_condition_2 ){

                return  

                sprintf( '<span class="badge text-dark border rounded p-2">%1$s</span>',

                    /**
                     *  1. Translation Ready String
                     *  ---------------------------
                     */
                    sprintf( esc_attr__( 'Guests %2$s', 'sdweddingdirectory-venue' ), 

                        /**
                         *  1. Min Seat
                         *  -----------
                         */
                        absint( '1' ),

                        /**
                         *  2. Max Seat
                         *  -----------
                         */
                        absint( $_post_have_seat_data )
                    )
                );
            }
        }

        /**
         *  Venue Post Expert Content
         *  ---------------------------
         */
        public static function venue_post_expert_content( $post_id = '' ){

            if( empty( $post_id ) ){
                return;
            }

            global $post, $wp_query;

            if( empty( get_the_excerpt( $post_id ) ) ){
                return;
            }

            $_content_length    =   absint( '28' );

            /**
             *  @credit - https://developer.wordpress.org/reference/functions/wp_strip_all_tags/#user-contributed-notes
             *  -------------------------------------------------------------------------------------------------------
             */
            $_content   =   wp_strip_all_tags(

                                htmlspecialchars_decode( 

                                    apply_filters( 'sdweddingdirectory_clean_shortcode', get_the_excerpt( $post_id ) )
                                )
                            );

            // $_content   =   get_the_excerpt( $post_id );

            $excerpt = explode(' ', $_content, $_content_length );

            if ( count($excerpt) >= $_content_length ) {

                array_pop($excerpt);

                $excerpt = implode( " ", $excerpt ) . ' ...';

            } else {

                $excerpt = implode(" ",$excerpt);
            }

            $excerpt = preg_replace('`[[^]]*]`','',$excerpt  );

            return  $excerpt;

            /**
             *  Right now : FALSE
             *  -----------------
             */
            return

            sprintf( '<p>%1$s</p>',

                /**
                 *  1. Return Expert Content
                 *  ------------------------
                 */
                $excerpt
            );
        }

        /**
         *  Venue Price Text
         *  ------------------
         */
        public static function venue_post_price_text( $post_id = '' ){

            if( empty( $post_id ) ){

                return '';
            }

            $_min_price = get_post_meta( absint( $post_id ), sanitize_key( 'venue_min_price' ), true );

            $_max_price = get_post_meta( absint( $post_id ), sanitize_key( 'venue_max_price' ), true );

            if( absint( $_min_price ) <= absint( '0' ) && absint( $_max_price ) <= absint( '0' ) ){

                return '';
            }

            return sprintf( '%1$s - %2$s',

                        sdweddingdirectory_pricing_possition( absint( $_min_price ) ),

                        sdweddingdirectory_pricing_possition( absint( $_max_price ) )
                    );
        }

        /**
         *  Venue Capacity Text
         *  ---------------------
         */
        public static function venue_post_capacity_text( $post_id = '' ){

            if( empty( $post_id ) ){

                return '';
            }

            $seat_capacity = get_post_meta( absint( $post_id ), sanitize_key( 'venue_seat_capacity' ), true );

            $capacity_enabled = apply_filters( 'sdweddingdirectory/capacity-enable', [

                                    'term_id'       =>      apply_filters( 'sdweddingdirectory/post/term-parent', [

                                                                'post_id'       =>      absint( $post_id ),

                                                                'taxonomy'      =>      esc_attr( 'venue-type' )
                                                            ] )
                                ] );

            $is_enabled = $capacity_enabled == true || $capacity_enabled == 'true' || $capacity_enabled == absint( '1' );

            if( ! parent:: _have_data( $seat_capacity ) || ! $is_enabled ){

                return '';
            }

            return sprintf( esc_attr__( 'Up to %1$s guests', 'sdweddingdirectory-venue' ), absint( $seat_capacity ) );
        }

        /**
         *  1. Load Pricing for venue thumb
         *  ---------------------------------
         */
        public static function venue_post_price( $args = [] ){

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

                    'post_id'       =>      absint( '0' )

                ] ) );

                /**
                 *  Have Args ?
                 *  -----------
                 */
                if( empty( $post_id ) ){

                    return;
                }

                $_min_price     =   get_post_meta( absint( $post_id ), sanitize_key( 'venue_min_price' ), true );

                $_max_price     =   get_post_meta( absint( $post_id ), sanitize_key( 'venue_max_price' ), true );

                $_condition_1   =   $_min_price > absint( '0' );

                $_condition_2   =   $_max_price > absint( '0' );

                /**
                 *  Pricing + Capacity Tag
                 *  ----------------------
                 */
                $output = '';

                if( $_condition_1 ){

                    $output .= sprintf(
                        '<span class="price"><i class="fa fa-tag"></i><span>%1$s %2$s</span></span>',
                        esc_html__( 'Starting at', 'sdweddingdirectory-venue' ),
                        sdweddingdirectory_pricing_possition( absint( $_min_price ) )
                    );
                }

                $seat_capacity = get_post_meta( absint( $post_id ), sanitize_key( 'venue_seat_capacity' ), true );

                if( absint( $seat_capacity ) > 0 ){

                    $output .= sprintf(
                        '<span class="capacity"><i class="fa fa-users"></i><span>%1$s</span></span>',
                        absint( $seat_capacity )
                    );
                }

                if( ! empty( $output ) ){

                    return $output;
                }
            }
        }

        /**
         *  2. Load Category for venue
         *  ----------------------------
         */
        public static function venue_post_category( $post_id = '0' ){

            /**
             *  Make sure post id not emtpy!
             *  ----------------------------
             */
            if( empty( $post_id ) ){

                return;
            }

            /**
             *  Extract
             *  -------
             */
            extract( [

                'data'          =>      '',

                'term_id'       =>      apply_filters( 'sdweddingdirectory/post/term-parent', [

                                            'post_id'       =>      absint( $post_id ),

                                            'taxonomy'      =>      esc_attr( 'venue-type' )

                                        ] )
            ] );

            /**
             *  Make sure term id not emtpy!
             *  ----------------------------
             */
            if( ! empty( $term_id ) ){

                /**
                 *  Data
                 *  ----
                 */
                $data      .=      sprintf(   '<a class="tags" href="%1$s">%2$s</a>',

                                                /**
                                                 *  1. Min Price
                                                 *  ------------
                                                 */
                                                esc_url( get_category_link( $term_id ) ),

                                                /**
                                                 *  2. Max Price
                                                 *  ------------
                                                 */
                                                esc_attr( get_the_category_by_ID( $term_id ) )
                                    );
            }

            /**
             *  After Tag Have Filter ?
             *  -----------------------
             */
            return      $data   .   apply_filters( 'sdweddingdirectory/venue/post/after-tag-filter', '', $post_id );
        }

        /**
         *  3. Load Wishlist Icon for venue
         *  ---------------------------------
         */
        public static function venue_post_wishlist( $post_id = '' ){

            /**
             *  Make sure post id not emtpy!
             *  ----------------------------
             */
            if( empty( $post_id ) ){

                return;
            }

            if( class_exists( 'SDWeddingDirectory_WishList_Filters' ) ){

                return      apply_filters(  'sdweddingdirectory/post/wishlist', [

                                'post_id'       =>      absint( $post_id ),

                                'layout'        =>      absint( '1' )

                            ] );
            }
        }

        /**
         *  4. Is Verify Vendor ?
         *  ---------------------
         */
        public static function verify_badge( $args = [] ){

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

                    'post_id'           =>      absint( '0' ),

                    'layout'            =>      absint( '1' ),

                    'verify_string'     =>      apply_filters( 'sdweddingdirectory/venue/verify-badge/string', 

                                                    /**
                                                     *  1. Default
                                                     *  ----------
                                                     */
                                                    esc_attr__( 'Verified Venue', 'sdweddingdirectory-venue' )
                                                )

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return;
                }

                /**
                 *  Is Verify ?
                 *  -----------
                 */
                $_is_verify     =   get_post_meta( absint( $post_id ), sanitize_key( 'venue_verified' ), true );

                /**
                 *  This task is pending due to verify vendor have plugin to check is verify vendor or not ?
                 *  ----------------------------------------------------------------------------------------
                 */
                if( $_is_verify == esc_attr( 'on' ) ){

                    /**
                     *  Layout 1
                     *  --------
                     */
                    if( $layout == absint( '1' ) ){

                        /**
                         *  Layout 1
                         *  --------
                         */
                        return      sprintf(    '<span  class="venue-verify-badge-style-one"

                                                    data-bs-toggle="tooltip" 

                                                    data-bs-placement="top" 

                                                    title="%1$s">

                                                    <i class="fa fa-check-circle"></i>

                                                </span>',

                                                /**
                                                 *  1. Translation Ready String
                                                 *  ---------------------------
                                                 */
                                                esc_attr( $verify_string )
                                    );
                    }

                    /**
                     *  Layout 2
                     *  --------
                     */
                    if( $layout == absint( '2' ) ){

                        /**
                         *  Layout 1
                         *  --------
                         */
                        return      sprintf(    '<span  class="venue-verify-badge-style-two" 

                                                        data-bs-toggle="tooltip" 

                                                        data-bs-placement="top" 

                                                        title="%1$s">

                                                    <i class="fa fa-check-circle"></i>

                                                </span>', 

                                                /**
                                                 *  1. Translation Ready String
                                                 *  ---------------------------
                                                 */
                                                esc_attr( $verify_string )
                                    );
                    }                    
                }
            }
        }

        /**
         *  5. Venue Location
         *  -------------------
         */
        public static function venue_post_location( $post_id = '' ){

            /**
             *  Make sure post id not empty!
             *  ----------------------------
             */
            if( empty( $post_id ) ){

                return;
            }

            /**
             *  Return
             *  ------
             */
            return      apply_filters( 'sdweddingdirectory/post/location', [

                            'post_id'       =>      absint( $post_id ),

                            'taxonomy'      =>      esc_attr( 'venue-location' ),

                            'before'        =>      '<p class="mb-0">',

                            'after'         =>      '</p>'

                        ] );
        }

        /**
         *  6. Venue Review
         *  -----------------
         */
        public static function venue_post_review( $post_id = '' ){

            /**
             *  Make sure id not empty!
             *  -----------------------
             */
            if( empty( $post_id ) ){

                return;
            }

            /**
             *  Have Rating ?
             *  -------------
             */
            $found_rating       =       apply_filters( 'sdweddingdirectory/rating/found', '', [

                                            'venue_id'  =>  absint( $post_id ),

                                        ] );

            /**
             *  Found At least 1 rating
             *  -----------------------
             */
            if( $found_rating >= absint( '1' ) ){

                /**
                 *  Return Post Rating
                 *  ------------------
                 */
                return      sprintf(    '<div class="reviews">

                                            <div class="stars sdweddingdirectory_review rating-star" data-review="%1$s"></div>

                                            <span>( %2$s %3$s )</span>

                                        </div>',

                                        /**
                                         *  1. Get Average Rating
                                         *  ---------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/rating/average', '', [

                                            'venue_id'        =>      absint( $post_id ),

                                        ] ),

                                        /**
                                         *  2. Total Reviews
                                         *  ----------------
                                         */
                                        apply_filters( 'sdweddingdirectory/rating/found', '', [

                                            'venue_id'  =>  absint( $post_id ),

                                        ] ),

                                        /**
                                         *  3. Translation Ready String
                                         *  ---------------------------
                                         */
                                        esc_attr__( 'Reviews', 'sdweddingdirectory-venue' )
                            );
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Venue:: get_instance();
}
