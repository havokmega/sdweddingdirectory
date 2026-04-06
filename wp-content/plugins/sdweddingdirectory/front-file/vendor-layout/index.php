<?php
/**
 *   SDWeddingDirectory - Vendor Post Layout
 *   -------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor' ) && class_exists( 'SDWeddingDirectory_Front_End_Modules' ) ){

    /**
     *   SDWeddingDirectory - Vendor Post Layout
     *   -------------------------------
     */
    class SDWeddingDirectory_Vendor extends SDWeddingDirectory_Front_End_Modules{

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
             *  1. SDWeddingDirectory - Placeholder Filter
             *  ----------------------------------
             */
            add_filter( 'sdweddingdirectory/placeholder', function( $args = [] ){

                /**
                 *  Add Slider Placeholder
                 *  ----------------------
                 */
                return  array_merge(

                            /**
                             *  Have Args ?
                             *  -----------
                             */
                            $args,

                            /**
                             *  Merge New Args
                             *  --------------
                             */
                            array(

                                [
                                    'name'              =>  esc_attr__( 'Vendor Post', 'sdweddingdirectory' ),

                                    'id'                =>  esc_attr( 'vendor-post' ),

                                    'placeholder'       =>  [

                                        'vendor-post'   =>  esc_url(  plugin_dir_url( __FILE__ ) . 'images/' . 'vendor-post.jpg' ),

                                        'author-pic'    =>  esc_url(  plugin_dir_url( __FILE__ ) . 'images/' . 'logo.png' ),

                                    ]
                                ],
                            )
                        );
            } );

            /**
             *  1. SDWeddingDirectory - Vendor Post Image Size
             *  --------------------------------------
             */
            add_filter( 'sdweddingdirectory/image-size', function( $args = [] ){

                return  array_merge( 

                            $args,

                            array(

                                [
                                    'id'            =>      esc_attr( 'sdweddingdirectory_img_600x385' ),

                                    'name'          =>      esc_attr__( 'Vendor Post Image', 'sdweddingdirectory' ),

                                    'width'         =>      absint( '600' ),

                                    'height'        =>      absint( '385' )
                                ],
                            )
                        );
            } );

            /**
             *  Vendor Post
             *  -----------
             */
            add_filter( 'sdweddingdirectory/vendor/post', [ $this, 'sdweddingdirectory_vendor_layout' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Vendor Featured Image
         *  ----------------------
         */
        public static function vendor_post_image( $post_id = '' ){

            if( empty( $post_id ) ){

                return;
            }

            /**
             *  1. Vendor Post Image
             *  --------------------
             */
            $_media_id  =   get_post_meta( absint( $post_id ), sanitize_key( 'profile_banner' ), true );

            /**
             *  Return Profile Banner Path
             *  --------------------------
             */
            return      parent:: _have_media( $_media_id )

                        ?   apply_filters( 'sdweddingdirectory/media-data', [

                                'media_id'         =>  absint( $_media_id ),

                                'image_size'       =>  esc_attr( 'sdweddingdirectory_img_600x385' ),

                            ] )

                        :   esc_url( parent:: placeholder( 'vendor-post' ) );
        }

        /**
         *  Image Alt
         *  ---------
         */
        public static function vendor_post_image_alt( $post_id = '' ){

            /**
             *  Media ID
             *  --------
             */
            $media_id               =   get_post_meta( absint( $post_id ), sanitize_key( 'profile_banner' ), true );

            /**
             *  Return : Image Alt
             *  ------------------
             */
            return  esc_attr( parent:: _alt( array(

                        'media_id'      =>  absint( $media_id ), 

                        'post_id'       =>  absint( $post_id ),

                        'start_alt'     =>  sprintf( esc_attr__( '%1$s Category Vendor', 'sdweddingdirectory' ),

                                                /**
                                                 *  1. Get Category Name
                                                 *  --------------------
                                                 */
                                                apply_filters( 'sdweddingdirectory/post/term-parent', [

                                                    'get_data'      =>      esc_attr( 'name' ),

                                                    'taxonomy'      =>      esc_attr( 'vendor-category' ),

                                                    'post_id'       =>      absint( $post_id )

                                                ] )
                                            )
                    ) ) );
        }

        /**
         *  Have Location ?
         *  ---------------
         */
        public static function have_location_taxonomy( $post_id = '' ){
            return '';
        }

        /**
         *  Location For Result Cards
         *  -------------------------
         */
        public static function vendor_result_location( $post_id = '' ){
            return '';
        }

        /**
         *  Excerpt For Result Cards
         *  ------------------------
         */
        public static function vendor_result_excerpt( $post_id = '' ){

            if( empty( $post_id ) ){

                return '';
            }

            $excerpt = get_the_excerpt( absint( $post_id ) );

            if( empty( $excerpt ) ){

                $excerpt = get_post_field( 'post_content', absint( $post_id ) );
            }

            if( empty( $excerpt ) ){

                return '';
            }

            $excerpt = wp_strip_all_tags(
                            htmlspecialchars_decode(
                                apply_filters( 'sdweddingdirectory_clean_shortcode', $excerpt )
                            )
                        );

            return wp_trim_words( $excerpt, absint( '24' ), '...' );
        }

        /**
         *  Pricing For Result Cards
         *  ------------------------
         */
        public static function vendor_result_price( $post_id = '' ){

            if( empty( $post_id ) ){

                return '';
            }

            $price_data = maybe_unserialize( get_post_meta( absint( $post_id ), sanitize_key( 'vendor_pricing' ), true ) );

            $price_list = [];

            if( is_array( $price_data ) ){

                foreach( $price_data as $price_item ){

                    $value = sanitize_text_field( $price_item );

                    if( $value !== '' ){

                        $price_list[] = $value;
                    }
                }
            }

            elseif( ! empty( $price_data ) ){

                $price_list[] = sanitize_text_field( $price_data );
            }

            $price_list = array_values( array_unique( array_filter( $price_list ) ) );

            if( ! parent:: _is_array( $price_list ) ){

                return '';
            }

            return implode( ', ', array_slice( $price_list, 0, absint( '3' ) ) );
        }

        /**
         *  Get Venodr Category Page link
         *  -----------------------------
         */
        public static function get_vendor_category_link( $post_id = '' ){

            /**
             *  Make sure post id not empty!
             *  ----------------------------
             */
            if( empty( $post_id ) ){

                return;
            }

            /**
             *  Return Name
             *  -----------
             */
            return          apply_filters( 'sdweddingdirectory/post/term-parent', [

                                'post_id'       =>      absint( $post_id ),

                                'taxonomy'      =>      esc_attr( 'vendor-category' ),

                                'get_data'      =>      esc_attr( 'url' )

                            ] );
        }

        /**
         *  Get Vendor Category Icon
         *  ------------------------
         */
        public static function vendor_post_icon_section( $post_id = '' ){

            /**
             *  Post ID
             *  -------
             */
            if( empty( $post_id ) ){

                return;
            }

            /**
             *  Venue Post ID to get Parent Category ID
             *  -----------------------------------------
             */
            $term_id    =   apply_filters( 'sdweddingdirectory/post/term-parent', [

                                'post_id'           =>      absint( $post_id ),

                                'taxonomy'          =>      esc_attr( 'vendor-category' ),

                                '_key'              =>      absint( '0' )

                            ] );

            /**
             *  Return HTML
             *  -----------
             */
            return          sprintf(    '<div class="vendor-icon">

                                            <a href="%1$s"><i class="%2$s"></i></a>

                                        </div>',

                                        /**
                                         *  1. Icon
                                         *  -------
                                         */
                                        apply_filters( 'sdweddingdirectory/post/term-parent', [

                                            'get_data'      =>      esc_attr( 'url' ),

                                            'taxonomy'      =>      sanitize_key( 'vendor-category' ),

                                            'post_id'       =>      absint( $post_id )

                                        ] ),

                                        /**
                                         *  1. Icon
                                         *  -------
                                         */
                                        apply_filters( 'sdweddingdirectory/term/icon', [

                                            'term_id'       =>      absint( $term_id ),

                                            'taxonomy'      =>      sanitize_key( 'vendor-category' )

                                        ] )
                            );
        }

        /**
         *  Share Button
         *  ------------
         */
        public static function vendor_post_share_button( $post_id = '0' ){

            if( empty( $post_id ) ){

                return;
            }

            /**
             *  Have Social Media Share Object ?
             *  --------------------------------
             */
            
            return 

            sprintf( '<a class="btn btn-outline-white sdweddingdirectory-share-post-model" data-post-id="%1$s" href="javascript:"><i class="fa fa-share-alt"></i> %2$s</a>', 

                /**
                 *  1. Post ID
                 *  ----------
                 */
                absint( $post_id ),

                /**
                 *  2. Translation Ready String
                 *  ---------------------------
                 */
                esc_attr__( 'Share', 'sdweddingdirectory' )
            );
        }

        /**
         *  Vendor Post Layout
         *  ------------------
         */
		public static function sdweddingdirectory_vendor_layout( $args = [] ){

            /**
             *  Global Args
             *  -----------
             */
            global $wp_query, $post;

            /**
             *  Have Args
             *  ---------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'layout'        =>      absint( '1' ),

                    'post_id'       =>      absint( '0' ),

                    'collection'    =>      ''

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return;
                }

                /**
                 *  Vendor Query
                 *  ------------
                 */
                $_vendor_query         	= 	new WP_Query( array(

                                                'post_type'         => 	esc_attr( 'vendor' ), 

                                                'post_status'       => 	esc_attr( 'publish' ),

                                                'post__in'          => 	array( $post_id )

                                            ) );
                /**
                 *  Have Post ?
                 *  -----------
                 */
                if ( $_vendor_query->have_posts() ){

                    /**
                     *  In Loop
                     *  -------
                     */
                    while ( $_vendor_query->have_posts() ){  $_vendor_query->the_post();

                        /**
                         *  Current Venue Post ID
                         *  -----------------------
                         */
                        $_post_id   =   absint( get_the_ID() );

                        /**
                         *  Vendor Args
                         *  -----------
                         */
                        extract( [

                            'post_permalink'        =>      esc_url( get_the_permalink( absint( $_post_id ) ) ),

                            'button_name'           =>      esc_attr__( 'View Suppliers', 'sdweddingdirectory' ),

                            'company_banner'        =>      self:: vendor_post_image( absint( $_post_id ) ),

                            'company_name'          =>      esc_attr( get_post_meta( absint( $_post_id ), sanitize_key( 'company_name' ), true ) ),

                            'post_location'         =>      '',

                            'category_icon'         =>      self:: vendor_post_icon_section( absint( $_post_id ) ),

                            'image_alt'             =>      esc_attr( self:: vendor_post_image_alt( absint( $_post_id ) ) ),

                            'company_email'         =>      get_post_meta( absint( $_post_id ), sanitize_key( 'company_email' ), true ),

                            'company_contact'       =>      get_post_meta( absint( $_post_id ), sanitize_key( 'company_contact' ), true ),

                            'post_share'            =>      self:: vendor_post_share_button( absint( $_post_id ) ),

                            'handler'               =>      '',

                            'vendor_rating'         =>      apply_filters( 'sdweddingdirectory/rating/average', '', [

                                                                'vendor_id'         =>      absint( $_post_id ),

                                                                'before'            =>      '<span class="rating">',

                                                                'after'             =>      '</span>',

                                                                'icon'              =>      '<i class="fa fa-star-half-full me-1"></i>'

                                                            ] ),

                            'follow_button'         =>      apply_filters( 'sdweddingdirectory/vendor/following', '', [

                                                                'post_id'       =>      absint( $_post_id )

                                                            ] ),
                        ] );


                        /**
                         *  Vendor Layout 1
                         *  ---------------
                         */
                        if( $layout == absint( '1' ) ){

                            /**
                             *  Get Venodr Post Layout data
                             *  ---------------------------
                             */
                            $collection   .=

                            sprintf(    '<div class="vendor-venue-wrap">

                                            <div class="vendor-img">

                                                <div class="overlay-box">

                                                    <a href="%1$s" class="btn btn-default btn-rounded btn-sm">%2$s</a> 

                                                </div>

                                                %6$s <!-- Vendor Category Icon -->

                                                <a href="%1$s">

                                                    <img class="w-100" src="%3$s" alt="%7$s">
                                                </a>

                                            </div>

                                            <div class="content">

                                                <h3><a href="%1$s">%4$s</a></h3>

                                                %5$s <!-- Vendor Location -->

                                            </div>

                                        </div>',

                                        /**
                                         *  1. Vendor Singular Page Link
                                         *  ----------------------------
                                         */
                                        esc_url( $post_permalink ),

                                        /**
                                         *  2. Translation Ready Button Text
                                         *  --------------------------------
                                         */
                                        esc_attr( $button_name ),

                                        /**
                                         *  3. Vendor Post Thumbnails Image
                                         *  -------------------------------
                                         */
                                        $company_banner,

                                        /**
                                         *  4. Vendor Company Name
                                         *  ----------------------
                                         */
                                        esc_attr( $company_name ),

                                        /**
                                         *  5. Post Location
                                         *  ----------------
                                         */
                                        '',

                                        /**
                                         *  6. Vendor Category Icon
                                         *  -----------------------
                                         */
                                        $category_icon,

                                        /**
                                         *  7. Image Alt
                                         *  ------------
                                         */
                                        esc_attr(  $image_alt  )
                            );
                        }

                        /**
                         *  Vendor Layout 2
                         *  ---------------
                         */
                        if( $layout == absint( '2' ) ){

                            /**
                             *  Company Email
                             *  -------------
                             */
                            if( ! empty( $company_email ) ){

                                $handler    .=

                                sprintf(  ' <li><i class="fa fa-envelope me-2"></i> <a href="mailto:%1$s" title="%1$s" class="btn-link">%1$s</a></li>',

                                            /**
                                             *  1. Get Vendor Email
                                             *  -------------------
                                             */
                                            sanitize_email(

                                                /**
                                                 *  1. get backend email id via vendor post
                                                 *  ---------------------------------------
                                                 */
                                                get_post_meta( absint( $_post_id ), sanitize_key( 'company_email' ), true ) 
                                            )
                                );
                            }

                            /**
                             *  Company Contact Number
                             *  ----------------------
                             */
                            if( ! empty( $company_contact ) ){

                                $handler    .=

                                sprintf(    '<li><i class="fa fa-phone me-2"></i> <a href="tel:%1$s" title="%2$s" class="btn-link">%2$s</a></li>',

                                            /**
                                             *  1. Get Vendor Contact Information
                                             *  ---------------------------------
                                             */
                                            esc_attr( 

                                                preg_replace( '/[^\d+]/', '', 

                                                    get_post_meta( absint( $_post_id ), sanitize_key( 'company_contact' ), true )  
                                                )
                                            ),

                                            /**
                                             *  2. Click to Call Tel
                                             *  --------------------
                                             */
                                            esc_attr(

                                                /**
                                                 *  1. get backend email id via vendor post
                                                 *  ---------------------------------------
                                                 */
                                                get_post_meta( absint( $_post_id ), sanitize_key( 'company_contact' ), true ) 
                                            )
                                    );
                            }

                            /**
                             *  Get Venodr Post Layout data
                             *  ---------------------------
                             */
                            $collection   .=

                            sprintf(   '<div class="vendor-wrap-alt">

                                            <div class="img">

                                                <a href="%1$s">

                                                    <img class="w-100" src="%2$s" alt="%3$s">

                                                </a>

                                                <div class="img-content">%4$s</div>

                                            </div>

                                            <div class="content border-bottom py-3">

                                                <div class="vendor-heading mb-0">

                                                    <h3>%5$s <a href="%1$s">%6$s</a></h3>

                                                </div>

                                            </div>

                                            <div class="content py-2">

                                                %7$s <!-- Have Email for Vendor -->

                                            </div>

                                            <div class="content-footer">

                                                %8$s <!-- Follow Vendor Button -->

                                                %9$s <!-- Share Vendor Option -->

                                            </div>

                                        </div>',

                                        /**
                                         *  1. Vendor Singular Page Link
                                         *  ----------------------------
                                         */
                                        esc_url( $post_permalink ),

                                        /**
                                         *  2. Vendor Post Thumbnails Image
                                         *  -------------------------------
                                         */
                                        $company_banner,

                                        /**
                                         *  3. Image Alt
                                         *  ------------
                                         */
                                        esc_attr(  $image_alt  ),

                                        /**
                                         *  4. Vendor Rating
                                         *  ----------------
                                         */
                                        $vendor_rating,

                                        /**
                                         *  5. Vendor Category Icon
                                         *  -----------------------
                                         */
                                        $category_icon,

                                        /**
                                         *  6. Vendor Company Name
                                         *  ----------------------
                                         */
                                        esc_attr( $company_name ),

                                        /**
                                         *  7. Vendor Have Email ?
                                         *  ----------------------
                                         */
                                        ! empty( $handler )

                                        ?   sprintf( '<ul class="vendor-contact-info">%1$s</ul>', $handler )

                                        :   '',

                                        /**
                                         *  8. Follow Vendor
                                         *  ----------------
                                         */
                                        $follow_button,

                                        /**
                                         *  9. Share Vendor
                                         *  ---------------
                                         */
                                        $post_share
                            );
                        }

                        /**
                         *  Vendor Layout 3
                         *  ---------------
                         */
                        if( $layout == absint( '3' ) ){

                            $vendor_name = parent:: _have_data( $company_name )

                                            ?   $company_name

                                            :   get_the_title( absint( $_post_id ) );

                            $vendor_location = self:: vendor_result_location( absint( $_post_id ) );

                            $vendor_excerpt  = self:: vendor_result_excerpt( absint( $_post_id ) );

                            $vendor_price    = self:: vendor_result_price( absint( $_post_id ) );

                            $collection   .=

                            sprintf( '<div class="sd-vendor-result-card mb-4">

                                        <div class="row g-0">

                                            <div class="col-md-4">

                                                <a href="%1$s">
                                                    <img src="%2$s" class="img-fluid rounded-start" alt="%3$s" />
                                                </a>

                                            </div>

                                            <div class="col-md-8">

                                                <div class="card-body p-3">

                                                    <h5 class="card-title mb-1">
                                                        <a href="%1$s">%4$s</a>
                                                    </h5>

                                                    %5$s

                                                    %6$s

                                                    %7$s

                                                    <a href="%1$s" class="btn btn-sm btn-outline-dark">%8$s</a>

                                                </div>

                                            </div>

                                        </div>

                                    </div>',

                                    esc_url( $post_permalink ),

                                    esc_url( $company_banner ),

                                    esc_attr( $image_alt ),

                                    esc_html( $vendor_name ),

                                    parent:: _have_data( $vendor_location )

                                    ?   sprintf( '<p class="text-muted small mb-2"><i class="fa fa-map-marker me-1"></i>%1$s</p>',

                                            esc_html( $vendor_location )
                                        )

                                    :   '',

                                    parent:: _have_data( $vendor_excerpt )

                                    ?   sprintf( '<p class="card-text small mb-2">%1$s</p>',

                                            esc_html( $vendor_excerpt )
                                        )

                                    :   '',

                                    parent:: _have_data( $vendor_price )

                                    ?   sprintf( '<p class="fw-bold mb-2">%1$s</p>',

                                            esc_html( $vendor_price )
                                        )

                                    :   '',

                                    esc_html__( 'Request Pricing', 'sdweddingdirectory' )
                            );
                        }
                    }

                    /**
                     *  Reset Query
                     *  -----------
                     */
                    if ( isset( $_vendor_query ) && $_vendor_query !== '' ) {

                        wp_reset_postdata();
                    }
                }

                /**
                 *  Return Venue box layout
                 *  -------------------------
                 */
                return $collection;

            }else{

                return;
            }

	    } // render end
    }

    /**
     *   SDWeddingDirectory - Vendor Post Layout
     *   -------------------------------
     */
    SDWeddingDirectory_Vendor::get_instance();
}
