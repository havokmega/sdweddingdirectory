<?php
/**
 *  -----------------------------------------
 *  SDWeddingDirectory - ShortCode - [ Venue Post ]
 *  -----------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Shortcode_Venue_Post' ) && class_exists( 'SDWeddingDirectory_Shortcode' ) ){

    /**
     *  -----------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Venue Post ]
     *  -----------------------------------------
     */
    class SDWeddingDirectory_Shortcode_Venue_Post extends SDWeddingDirectory_Shortcode {

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
         *  ShortCode Atts
         *  --------------
         */
        public static function default_args(){

            return      [
                            'layout'            =>  absint( '1' ),

                            'author'            =>  '',

                            'post_ids'          =>  '',

                            'posts_per_page'    =>  '',

                            'style'             =>  '',

                            'location_id'       =>  '',

                            'category_id'       =>  '',

                            'found_post'        =>  '',

                            'orderby'           =>  '',

                            'order'             =>  '',

                            'pagination'        =>  'true',

                            'badge'             =>  '',
                        ];
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  1. Find Venue Form Section
             *  ----------------------------
             */
            add_shortcode( 'sdweddingdirectory_venue', [ $this, 'sdweddingdirectory_venue' ] );

            /**
             *  SDWeddingDirectory - ShortCode Info
             *  ---------------------------
             */
            add_filter( 'sdweddingdirectory_shortcode_list', function( $args = [] ){

                /**
                 *  Get Plugin Information
                 *  ----------------------
                 */
                return  array_merge( $args, [

                            'sdweddingdirectory_venue'    =>  sprintf( '[sdweddingdirectory_venue %1$s][/sdweddingdirectory_venue]', 

                                                            parent:: _shortcode_atts( self:: default_args() )
                                                        )
                        ] );
            } );
        }

        /**
         *  Search Box Start
         *  ----------------
         */
        public static function sdweddingdirectory_venue( $atts, $content = null ){

            /**
             *  Extract Attr Data
             *  -----------------
             */
            extract( shortcode_atts( self:: default_args(), $atts, esc_attr( __FUNCTION__ ) ) );

            /**
             *  Var
             *  ---
             */
            $tax_query      =   $meta_query     =   [];

            /**
             *  Get Venue Category ID
             *  -----------------------
             */
            if( ! empty( $category_id ) ){

                $tax_query[]    =   array(

                    'taxonomy'  =>  esc_attr( 'venue-type' ),

                    'terms'     =>  preg_split ("/\,/", $category_id )
                );
            }

            /**
             *  Get Venue Category ID
             *  -----------------------
             */
            if( ! empty( $location_id ) ){

                $tax_query[]    =   array(

                    'taxonomy'  =>  esc_attr( 'venue-location' ),

                    'terms'     =>  preg_split ("/\,/", $location_id )
                );
            }

            /**
             *  Badge Wise Filter
             *  -----------------
             */
            if( ! empty( $badge ) ){

                /**
                 *  Venue Min Price Filter
                 *  ------------------------
                 */
                $meta_query[]   =   array(

                    'key'       =>  esc_attr( 'venue_badge' ),

                    'type'      =>  esc_attr( 'CHAR' ),

                    'compare'   =>  esc_attr( 'IN' ),

                    'value'     =>  preg_split ("/\,/", $badge )
                );
            }

            /**
             *  Handler
             *  -------
             */
            $collection     =   '';

            /**
             *  Current Page
             *  ------------
             */
            $paged          =       get_query_var( 'paged' )

                            ?       absint( get_query_var( 'paged' ) )

                            :       absint( '1' );

            /**
             *  Create Query
             *  ------------
             */
            $args           =       array_merge(

                                        /**
                                         *  Default Args
                                         *  ------------
                                         */
                                        array(

                                            'post_type'         =>  esc_attr( 'venue' ),

                                            'post_status'       =>  esc_attr( 'publish' ),

                                            'posts_per_page'    =>  -1
                                        ),

                                        /**
                                         *  Author ID
                                         *  ---------
                                         */
                                        parent:: _have_data( $author )

                                        ?   array(

                                                'author'     =>  absint( $author )
                                            )

                                        :   [],

                                        /**
                                         *  Have Shortcode Post ID set ?
                                         *  ----------------------------
                                         */
                                        parent:: _is_array( preg_split ("/\,/", $post_ids ) ) &&

                                        ! empty( $post_ids )

                                        ?   [   'post__in'  =>  preg_split ("/\,/", $post_ids )  ]

                                        :   [],

                                        /**
                                         *  Have Tax Query ?
                                         *  ----------------
                                         */
                                        parent:: _is_array( $tax_query )

                                        ?   [   'tax_query'        => [  'relation'  => 'AND', $tax_query  ]    ]

                                        :   [],

                                        /**
                                         *  4. Have Order By ?
                                         *  ------------------
                                         */
                                        !   empty( $orderby )

                                        ?   [   'orderby'       =>      $orderby  ]

                                        :   [],

                                        /**
                                         *  5. Have Order ?
                                         *  ---------------
                                         */
                                        !   empty( $order )

                                        ?   [   'order'       =>      $order  ]

                                        :   [],

                                        /**
                                         *  6. If Have Meta Query ?
                                         *  -----------------------
                                         */
                                        parent:: _is_array( $meta_query ) 

                                        ?   array(

                                                'meta_query'        => array(

                                                    'relation'  => 'AND',

                                                    $meta_query,
                                                )
                                            )

                                        :   []
                                    );


            /**
             *  WordPress to Find Query
             *  -----------------------
             */
            $query                          =       new WP_Query( $args );

            /**
             *  Get Post IDs
             *  ------------
             */
            $_post_id_collaction            =       wp_list_pluck( $query->posts, 'ID' );

            /**
             *  Make sure have collection
             *  -------------------------
             */
            if( parent:: _is_array( $_post_id_collaction ) ){

                /**
                 *  Get Collection
                 *  --------------
                 */
                $_post_id_collaction    =       apply_filters( 'sdweddingdirectory/venue/badge-filter', $_post_id_collaction );
            }

            else{

                $_post_id_collaction    =       [];
            }

            /**
             *  WordPress to Find Query
             *  -----------------------
             */
            $item               =   new WP_Query( array_merge( 

                                        /**
                                         *  Default Args
                                         *  ------------
                                         */
                                        $args,

                                        /**
                                         *  
                                         */
                                        array(

                                            'post__in'          =>  $_post_id_collaction,

                                            'orderby'           =>  esc_attr( 'post__in' ),

                                            'paged'             =>  absint( $paged ),
                                        ),

                                        /**
                                         *  Have Post Per Page ?
                                         *  --------------------
                                         */
                                        ! empty( $posts_per_page )

                                        ?   [  'posts_per_page' =>  $posts_per_page  ]

                                        :   [  'posts_per_page' =>  -1  ],

                                    ) );
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
                 *  WP_Query to get post ids collection
                 *  -----------------------------------
                 */
                $_get_posts_ids     =       wp_list_pluck( $item->posts, 'ID' );

                /**
                 *  Have Post Ids ?
                 *  ---------------
                 */
                if( parent:: _is_array( $_get_posts_ids ) ){

                    /**
                     *  In Loop
                     *  -------
                     */
                    foreach( $_get_posts_ids as $key ){

                        /**
                         *  Style
                         *  -----
                         */
                        $collection    .=      parent:: _shortcode_style_start( $style );

                        /**
                         *  Venue Structure
                         *  -----------------
                         */
                        $collection    .=       apply_filters( 'sdweddingdirectory/venue/post',

                                                    /**
                                                     *  1. Default Array
                                                     *  ----------------
                                                     */
                                                    [
                                                        'layout'    =>  absint( $layout ), 

                                                        'post_id'   =>  absint( $key )
                                                    ]
                                                );
                        /**
                         *  Style
                         *  -----
                         */
                        $collection    .=      parent:: _shortcode_style_end( $style );
                    }

                    /**
                     *  Have Pagination ?
                     *  -----------------
                     */
                    if( absint( $item->max_num_pages ) >= absint( '2' ) && $pagination  ==  esc_attr( 'true' ) ){

                        /**
                         *  Pagination
                         *  ----------
                         */
                        $collection   .=        apply_filters( 'sdweddingdirectory/pagination', [

                                                    'numpages'      =>  absint( $item->max_num_pages ),

                                                    'paged'         =>  absint( $paged )

                                                ] );
                    }
                }

                /**
                 *  Reset Shortcode Venue Query
                 *  -----------------------------
                 */
                if( isset( $item ) ){

                    wp_reset_postdata();
                }
            }

            /**
             *  ShortCode need to get total found result
             *  ----------------------------------------
             */
            if( $found_post == true ){

                /**
                 *  Return Found Result
                 *  -------------------
                 */
                return          absint( count( $_get_posts_ids ) );
            }

            /**
             *  Default : Return Posts
             *  ----------------------
             */
            else{

                /**
                 *  Return Button HTML
                 *  ------------------
                 */
                return          $collection;
            }
        }

        /**
         *  Page Builder : Args
         *  -------------------
         */
        public static function page_builder( $args = [] ){

            /** 
             *  Have Data ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Setting with Merge Empty Fields
                 *  ---------------------------------------
                 */
                extract( wp_parse_args( $args, self:: default_args() ) );

                /**
                 *  Return Button HTML
                 *  ------------------
                 */
                return  do_shortcode( 

                            sprintf(

                                '[sdweddingdirectory_venue 

                                    layout="%1$s" post_ids="%2$s" posts_per_page="%3$s" 

                                    style="%4$s" found_post=""

                                    orderby="%5$s" order="%6$s"  pagination="%7$s"

                                    category_id="%8$s" location_id="%9$s" badge="%10$s"

                                ][/sdweddingdirectory_venue]',

                                /**
                                 *  1. Layout
                                 *  ---------
                                 */
                                $layout,

                                /**
                                 *  2. Post IDs
                                 *  -----------
                                 */
                                parent:: _is_array( $post_ids )

                                ?   implode( ',', $post_ids )

                                :   $post_ids,

                                /**
                                 *  3. Post Per Page 
                                 *  ----------------
                                 */
                                $posts_per_page,

                                /**
                                 *  4. Have Style ?
                                 *  ---------------
                                 */
                                $style,

                                /**
                                 *  5. orderby
                                 *  ----------
                                 */
                                esc_attr( $orderby ),

                                /**
                                 *  6. Order
                                 *  --------
                                 */
                                esc_attr( $order ),

                                /**
                                 *  7. Pagination
                                 *  -------------
                                 */
                                esc_attr( $pagination ),

                                /**
                                 *  8. Category
                                 *  -----------
                                 */
                                parent:: _is_array( $category_id )

                                ?   implode( ',', $category_id )

                                :   $category_id,

                                /**
                                 *  9. Location
                                 *  -------------
                                 */
                                parent:: _is_array( $location_id )

                                ?   implode( ',', $location_id )

                                :   $location_id,

                                /**
                                 *  10. Badge
                                 *  ---------
                                 */
                                parent:: _is_array( $badge )

                                ?   implode( ',', $badge )

                                :   $badge
                            )
                        );
            }
        }
    }

    /**
     *  -----------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Venue Post ]
     *  -----------------------------------------
     */
    SDWeddingDirectory_Shortcode_Venue_Post::get_instance();
}