<?php
/**
 *  -------------------------------
 *  SDWeddingDirectory - Dropdown - Filters
 *  -------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Dropdown_Find_Category_With_Location_ID_Filters' ) && class_exists( 'SDWeddingDirectory_Dropdown_Filters' ) ){

    /**
     *  -------------------------------
     *  SDWeddingDirectory - Dropdown - Filters
     *  -------------------------------
     */
    class SDWeddingDirectory_Dropdown_Find_Category_With_Location_ID_Filters extends SDWeddingDirectory_Dropdown_Filters {

        /**
         *  A reference to an instance of this class
         *  ----------------------------------------
         */
        private static $instance;

        /**
         *  Returns an instance of this class.
         *  ----------------------------------
         */
        public static function get_instance() {

            if ( null == self::$instance ) {

                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  Find Category Terms by Location ID
             *  ----------------------------------
             */
            add_filter( 'sdweddingdirectory/find-category/location-id', [ $this, 'find_category_term_with_location_id' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Get Venue Category List
         *  -------------------------
         */
        public static function find_category_term_with_location_id( $args = [] ){

            /**
             *  Extract
             *  -------
             */
            extract( wp_parse_args( $args, [

                'state_id'          =>      absint( '0' ),

                'location'          =>      '',

                'region_id'         =>      absint( '0' ),

                'city_id'           =>      absint( '0' ),

                'category'          =>      [],

                'post_type'         =>      esc_attr( 'venue' ),

                'taxonomy'          =>      '',

                'allowed_terms'     =>      [],

                'location_id'       =>      absint( '0' ),

                'input_data'        =>      '',

                'handler'           =>      '',

                'find_terms'        =>      [],

                'find_posts'        =>      [],

                'post_break'        =>      absint( '5' ),

                'tax_query'         =>      []

            ] ) );

            /**
             *  Default taxonomy and category map
             *  ---------------------------------
             */
            if( empty( $taxonomy ) ){

                $taxonomy   =   $post_type == esc_attr( 'venue' )

                                ?   esc_attr( 'venue-type' )

                                :   esc_attr( $post_type . '-category' );
            }

            if( ! parent:: _is_array( $category ) || empty( $category ) ){

                $category   =   SDWeddingDirectory_Taxonomy:: get_taxonomy_parent( esc_attr( $taxonomy ) );
            }

            /**
             *  Vendor Mode: return selectable vendor category dropdown items
             *  -------------------------------------------------------------
             */
            if( $post_type == esc_attr( 'vendor' ) || $taxonomy == esc_attr( 'vendor-category' ) ){

                $vendor_terms = get_terms( [

                    'taxonomy'      =>      esc_attr( 'vendor-category' ),

                    'hide_empty'    =>      false,

                    'parent'        =>      absint( '0' ),

                    'orderby'       =>      'name',

                    'order'         =>      'ASC',
                ] );

                if( is_wp_error( $vendor_terms ) || ! parent:: _is_array( $vendor_terms ) ){

                    return  sprintf(   '<div class="available-category-in-location">

                                            <div class="list-of-category-list">

                                                <ul class="find-category-list sd-vendor-category-grid">

                                                    <li class="sd-vendor-category-item py-3 px-3">%1$s</li>

                                                </ul>

                                            </div>

                                        </div>',
                                        esc_attr__( 'No Category Found !', 'sdweddingdirectory' )
                            );
                }

                $vendor_items = [];

                foreach( $vendor_terms as $vendor_term ){

                    if( ! empty( $input_data ) && ! parent:: _have_word( $vendor_term->name, $input_data ) ){

                        continue;
                    }

                    $icon = apply_filters( 'sdweddingdirectory/term/icon', [

                        'term_id'       =>      absint( $vendor_term->term_id ),

                        'taxonomy'      =>      esc_attr( 'vendor-category' ),

                        'default_icon'  =>      ''
                    ] );

                    $vendor_items[] = sprintf(
                        '<li class="sd-vendor-category-item">

                            <a  class="search-item d-flex align-items-center"

                                href="javascript:"

                                data-collection="%1$s"

                                data-placeholder-name="%2$s">

                                %3$s%4$s
                            </a>

                        </li>',
                        esc_attr( json_encode( [

                            'term_id'       =>      absint( $vendor_term->term_id ),

                            'cat_id'        =>      absint( $vendor_term->term_id ),

                            'term_name'     =>      esc_attr( $vendor_term->name ),

                        ] ) ),
                        esc_attr( $vendor_term->name ),
                        ! empty( $icon ) ? sprintf( '<i class="%1$s me-3"></i>', esc_attr( $icon ) ) : '',

                        esc_attr( $vendor_term->name )
                    );
                }

                if( ! parent:: _is_array( $vendor_items ) || empty( $vendor_items ) ){

                    return  sprintf(   '<div class="available-category-in-location">

                                            <div class="list-of-category-list">

                                                <ul class="find-category-list sd-vendor-category-grid">

                                                    <li class="sd-vendor-category-item py-3 px-3">%1$s</li>

                                                </ul>

                                            </div>

                                        </div>',
                                        esc_attr__( 'No Category Found !', 'sdweddingdirectory' )
                            );
                }

                return  sprintf(   '<div class="available-category-in-location">

                                        <div class="list-of-category-list">

                                            <ul class="find-category-list sd-vendor-category-grid">%1$s</ul>

                                        </div>

                                    </div>',
                                    implode( '', $vendor_items )
                        );
            }

            /**
             *  Have City ID ?
             *  --------------
             */
            if( ! empty( $location ) ){

                $location_obj = get_term_by( 'slug', sanitize_title( $location ), esc_attr( $post_type . '-location' ) );

                if( ! empty( $location_obj ) && ! is_wp_error( $location_obj ) ){

                    $location_id = absint( $location_obj->term_id );
                }
            }

            elseif( ! empty( $city_id ) ){

                $location_id                    =       $city_id;
            }

            /**
             *  Have Region ID ?
             *  ----------------
             */
            elseif( ! empty( $region_id )  ){

                $location_id                    =       $region_id;
            }

            /**
             *  Have State ID ?
             *  ---------------
             */
            elseif( ! empty( $state_id )  ){

                $location_id                    =       $state_id;
            }

            /**
             *  Have Allowed Terms ?
             *  --------------------
             */
            if( parent:: _is_array(  $allowed_terms  ) ){

                $new_category       =       [];

                if( parent:: _is_array( $allowed_terms ) ){

                    foreach( $allowed_terms as $index =>  $term_id ){

                        if( array_key_exists(  $term_id, $category ) ){

                            $new_category[ absint( $term_id ) ]     =       apply_filters( 'sdweddingdirectory/term/name', [

                                                                                'term_id'       =>      $term_id,

                                                                                'taxonomy'      =>      esc_attr( $taxonomy )

                                                                            ] );
                        }
                    }
                }

                /**
                 *  Category Allowed
                 *  ----------------
                 */
                if( parent:: _is_array( $new_category ) ){

                    $category       =       $new_category;
                }
            }

            /**
             *  Make sure user write in category input
             *  --------------------------------------
             */
            if( ! empty( $input_data ) ){

                /**
                 *  Have Category Data
                 *  ------------------
                 */
                if( parent:: _is_array( $category ) ){

                    /**
                     *  Categories
                     *  ----------
                     */
                    foreach ( $category as $category_id => $category_name ){

                        /**
                         *  Found Tax
                         *  ---------
                         */
                        $_found_post    =   parent:: _term_exists( [

                                                'category_id'       =>      absint( $category_id ), 

                                                'location_id'       =>      absint( $location_id ),

                                                'post_type'         =>      esc_attr( $post_type )

                                            ] );

                        /**
                         *  If category id + location id both available in venue post ?
                         *  -------------------------------------------------------------
                         */
                        if( $_found_post ){

                            /**
                             *  Get input text similar string ?
                             *  -------------------------------
                             */
                            if (  parent:: _have_word( $category_name, $input_data )  ){

                                /**
                                 *  Categories
                                 *  ----------
                                 */
                                $term_link = get_term_link( absint( $category_id ), esc_attr( $taxonomy ) );

                                if( is_wp_error( $term_link ) ){

                                    $term_link = home_url( '/venues/' );
                                }

                                $find_terms[ $category_id ]   =

                                sprintf(   '<li>

                                                <a  class="search-item-redirect d-flex align-items-center"

                                                    href="%1$s">

                                                    <i class="%2$s me-3"></i>%3$s</a>

                                            </li>',

                                    /**
                                     *  1. Term Link
                                     *  ------------
                                     */
                                    esc_url( $term_link ),

                                    /**
                                     *  2. Icon
                                     *  -------
                                     */
                                    apply_filters( 'sdweddingdirectory/term/icon', [  'term_id'   =>   absint( $category_id )  ] ),

                                    /**
                                     *  3. Term Name
                                     *  ------------
                                     */
                                    parent:: _string_highlight( esc_attr( $category_name ), $input_data )
                                );
                            }
                        }
                    }
                }


               /**
                 *  Get Venue Category ID
                 *  -----------------------
                 */
                if( ! empty( $location_id ) ){

                    $tax_query[]    =   array(

                        'taxonomy'  =>  esc_attr( $post_type . '-location' ),

                        'terms'     =>  absint( $location_id )
                    );
                }

                /**
                 *  Find Venue Query
                 *  ------------------
                 */
                $args           =   array_merge(

                    /**
                     *  Default args
                     *  ------------
                     */
                    array(

                        'post_type'         =>  esc_attr( $post_type ),

                        'post_status'       =>  esc_attr( 'publish' ),

                        'posts_per_page'    =>  -1,
                    ),

                    /**
                     *  3. If Have Taxonomy Query ?
                     *  ---------------------------
                     */
                    parent:: _is_array( $tax_query ) 

                    ?   array(

                            'tax_query'        => array(

                                'relation'  => 'AND',

                                $tax_query,
                            )
                        )

                    :   []
                );

                /**
                 *  WordPress to Find Query
                 *  -----------------------
                 */
                $item               =   new WP_Query( $args );

                /**
                 *  Total Paged
                 *  -----------
                 */
                $item_total_page    =   absint( $item->max_num_pages );
                
                /**
                 *  Found Total Number of Venue
                 *  -----------------------------
                 */
                $total_element      =   $item->found_posts;

                /**
                 *  Have Venue at least 1 ?
                 *  -------------------------
                 */
                if( $total_element >= absint( '1' ) ){

                    /**
                     *  ------------------------------------------------------
                     *  Venue ID to return Badge wise priority ID show first
                     *  ------------------------------------------------------
                     *  1. Spotlight Venue Show
                     *  -------------------------
                     *  2. Featured Venue Show
                     *  ------------------------
                     *  3. Pro Venue Show
                     *  -------------------
                     *  4. Default Venue Show
                     *  -----------------------
                     */
                    $get_posts_ids     =   apply_filters( 'sdweddingdirectory/venue/badge-filter', wp_list_pluck( $item->posts, 'ID' ) );

                    /**
                     *  Have Post Ids ?
                     *  ---------------
                     */
                    if( parent:: _is_array( $get_posts_ids ) ){

                        foreach ( $get_posts_ids as $key ) {

                            /**
                             *  Check Post Title have String
                             *  ----------------------------
                             */
                            if (  parent:: _have_word( get_the_title( $key ), $input_data )  ){

                                $find_posts[ $key ]   =

                                sprintf(   '<li class="found-posts">

                                                <div class="row ">

                                                    <div class="col-3 px-1">

                                                        <a  class="search-item-redirect" href="%1$s">

                                                            <img src="%4$s" class="rounded" />

                                                        </a>

                                                    </div>

                                                    <div class="col-9">

                                                        <p class="mb-2 text-truncate">

                                                            <a  class="search-item-redirect" href="%1$s">%3$s</a>

                                                        </p>

                                                        %5$s

                                                    </div>

                                                </div>

                                            </li>',

                                    /**
                                     *  1. Get ID
                                     *  ---------
                                     */
                                    esc_url( get_the_permalink( $key ) ),

                                    /**
                                     *  2. Placeholder
                                     *  --------------
                                     */
                                    esc_attr( get_the_title( $key ) ),

                                    /**
                                     *  3. Get The Title
                                     *  ----------------
                                     */
                                    parent:: _string_highlight( esc_attr( get_the_title( $key ) ), $input_data ),

                                    /**
                                     *  4. Image
                                     *  --------
                                     */
                                    apply_filters( 'sdweddingdirectory/venue/post/thumbnail', [

                                        'post_id'           =>      absint( $key ),

                                        'image_size'        =>      esc_attr( 'thumbnail' )

                                    ] ),

                                    /**
                                     *  5. Location Info
                                     *  ----------------
                                     */
                                    apply_filters( 'sdweddingdirectory/post/location', [

                                        'post_id'           =>      $key,

                                        'button_class'      =>      'bg-white border-0 p-0 text-decoration-none text-muted ms-2',

                                        'before'            =>      '<p class="mb-0">',

                                        'after'             =>      '</p>'

                                    ] )
                                );

                                /**
                                 *  Counter
                                 *  -------
                                 */
                                $post_break--;
                            }

                            /**
                             *  Checking Counter 
                             *  ----------------
                             */
                            if( $post_break == absint( '0' ) ){

                                break;
                            }
                        }
                    }
                }

                /**
                 *  Have Terms
                 *  ----------
                 */
                if( parent:: _is_array( $find_terms ) ){

                    $handler        .=      sprintf(   '<div class="available-category">

                                                            <div class="card-header fw-bold py-3 user-select-none">%1$s</div>

                                                            <div class="find-category-list">

                                                                <ul class="sdweddingdirectory-category-posts-dropdown">%2$s</ul>

                                                            </div>

                                                        </div>',

                                                        /**
                                                         *  1. Translation Ready String
                                                         *  ---------------------------
                                                         */
                                                        esc_attr__( 'Wedding Venues', 'sdweddingdirectory' ),

                                                        /**
                                                         *  Find Terms
                                                         *  ----------
                                                         */
                                                        implode( '', $find_terms )
                                            );
                }

                /**
                 *  Have Posts
                 *  ----------
                 */
                if( parent:: _is_array( $find_posts ) ){

                    $handler        .=      sprintf(   '<div class="available-posts">

                                                            <div class="card-header fw-bold py-3 user-select-none">%1$s</div>

                                                            <div class="find-post-list">

                                                                <ul class="sdweddingdirectory-found-posts-dropdown">%2$s</ul>

                                                            </div>

                                                        </div>',

                                                        /**
                                                         *  1. Translation Ready String
                                                         *  ---------------------------
                                                         */
                                                        $post_type == esc_attr( 'vendor' )

                                                        ?   esc_attr__( 'Vendors', 'sdweddingdirectory' )

                                                        :   esc_attr__( 'Wedding Venues', 'sdweddingdirectory' ),

                                                        /**
                                                         *  Find Posts
                                                         *  ----------
                                                         */
                                                        implode( '', $find_posts )
                                            );
                }

                /**
                 *  Write Keyword in Category wise found terms and posts
                 *  ----------------------------------------------------
                 */
                return          $handler;
            }

            /**
             *  Collection of Category
             *  ----------------------
             */
            elseif( parent:: _is_array( $category ) ){

                /**
                 *  Categories
                 *  ----------
                 */
                foreach ( $category as $category_id => $category_name ) {

                    /**
                     *  Found Tax
                     *  ---------
                     */
                    $_found_post    =   parent:: _term_exists( [

                                            'category_id'       =>      absint( $category_id ),

                                            'location_id'       =>      absint( $location_id ),

                                            'post_type'         =>      esc_attr( $post_type )

                                        ] );

                    /**
                     *  If category id + location id both available in venue post ?
                     *  -------------------------------------------------------------
                     */
                    if( $_found_post  ){

                        $term_link       =       get_term_link( absint( $category_id ), esc_attr( $taxonomy ) );

                        if( is_wp_error( $term_link ) ){

                            $term_link   =       home_url( '/venues/' );
                        }

                        if( ! empty( $location_id ) ){

                            $location_obj = get_term( absint( $location_id ), esc_attr( $post_type . '-location' ) );

                            if( ! empty( $location_obj ) && ! is_wp_error( $location_obj ) ){

                                $term_link = add_query_arg( [

                                    'location'  =>      sanitize_title( $location_obj->slug )

                                ], $term_link );
                            }
                        }

                        $handler  .=

                        sprintf(   '<li>

                                        <a  class="search-item-redirect" href="%1$s">%2$s</a>

                                    </li>',

                            /**
                             *  1. Term Link
                             *  -------------
                             */
                            esc_url( $term_link ),

                            /**
                             *  2. Full String
                             *  --------------
                             */
                            esc_attr( $category_name )
                        );
                    }
                }


                /**
                 *  Location ID Found !
                 *  -------------------
                 */
                if( ! empty( $location_id ) && ! empty( $handler ) ){

                    /**
                     *  Found Term Id to Taxonomy
                     *  -------------------------
                     */
                    $taxonomy           =       apply_filters( 'sdweddingdirectory/term-id/tax', $location_id );

                    /**
                     *  Default Lable
                     *  -------------
                     */
                    $label              =       esc_html__( 'Wedding Venues', 'sdweddingdirectory' );

                    /**
                     *  Found Taxonomy ?
                     *  ----------------
                     */
                    if( ! empty( $taxonomy ) ){

                        /**
                         *  Location Object
                         *  ---------------
                         */
                        $location_obj       =       get_term( $location_id, $taxonomy );

                        /**
                         *  Lable
                         *  -----
                         */
                        $label              =       sprintf( esc_html__( 'Wedding Venues in %1$s %2$s', 'sdweddingdirectory' ),

                                                        /**
                                                         *  1. Location Name
                                                         *  ----------------
                                                         */
                                                        esc_attr( $location_obj->name ),

                                                        /**
                                                         *  Is City / State / Region ?
                                                         *  --------------------------
                                                         */
                                                        apply_filters( 'sdweddingdirectory/term-id/area-name', [

                                                            'term_id'       =>      $location_id,

                                                            'taxonomy'      =>      $taxonomy

                                                        ] )
                                                    );
                    }

                    /**
                     *  Return Data
                     *  -----------
                     */
                    return          sprintf(   '<div class="available-category-in-location">

                                                    <div class="card-header fw-bold py-3 user-select-none">%1$s</div>

                                                    <div class="list-of-category-list">

                                                        <ul class="find-category-list">%2$s</ul>

                                                    </div>

                                                </div>',

                                                /**
                                                 *  1. Found Location by Category
                                                 *  -----------------------------
                                                 */
                                                $label,

                                                /**
                                                 *  2. Get list
                                                 *  -----------
                                                 */
                                                $handler
                                    );
                }

                /**
                 *  Display Category Terms
                 *  ----------------------
                 */
                else{

                    /**
                     *  Make sure have terms data
                     *  -------------------------
                     */
                    if( ! empty( $handler ) ){

                        $all_venues_item = sprintf(
                            '<li class="sd-all-venues-link"><a class="search-item-redirect fw-bold" href="%1$s">%2$s</a></li>',
                            esc_url( home_url( '/venues/' ) ),
                            esc_attr__( 'All Venues', 'sdweddingdirectory' )
                        );

                        return          sprintf(   '<div class="available-category-in-location">

                                                        <div class="card-header fw-bold py-3 user-select-none">%1$s</div>

                                                        <div class="list-of-category-list">

                                                            <ul class="find-category-list">%2$s%3$s</ul>

                                                        </div>

                                                    </div>',

                                                    /**
                                                     *  1. Label
                                                     *  --------
                                                     */
                                                    esc_attr__( 'Wedding Venues', 'sdweddingdirectory' ),

                                                    /**
                                                     *  2. All Venues Link
                                                     *  -------------------
                                                     */
                                                    $all_venues_item,

                                                    /**
                                                     *  3. Category Links
                                                     *  ------------------
                                                     */
                                                    $handler
                                        );
                    }

                    /**
                     *  No Terms Found !
                     *  ----------------
                     */
                    else{

                        return          sprintf(   '<div class="available-category-in-location">

                                                        <div class="card-header fw-bold py-3 user-select-none">%1$s</div>

                                                    </div>',

                                                    /**
                                                     *  1. Found Location by Category
                                                     *  -----------------------------
                                                     */
                                                    esc_attr__( 'No Category Found !', 'sdweddingdirectory' )
                                        );
                    }
                }
            }
        }
    }

    /**
     *  -------------------------------
     *  SDWeddingDirectory - Dropdown - Filters
     *  -------------------------------
     */
    SDWeddingDirectory_Dropdown_Find_Category_With_Location_ID_Filters::get_instance();
}
