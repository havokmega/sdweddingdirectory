<?php
/**
 *  ----------------------------------------
 *  SDWeddingDirectory - ShortCode - [ Vendor Post ]
 *  ----------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Shortcode_Vendor_Post' ) && class_exists( 'SDWeddingDirectory_Shortcode' ) ){

    /**
     *  ----------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Vendor Post ]
     *  ----------------------------------------
     */
    class SDWeddingDirectory_Shortcode_Vendor_Post extends SDWeddingDirectory_Shortcode {

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
                            'layout'            =>      absint( '1' ),

                            'post_ids'          =>      '',

                            'posts_per_page'    =>      '',

                            'style'             =>      '',

                            'orderby'           =>      '',

                            'order'             =>      '',

                            'pagination'        =>      'true'
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
            add_shortcode( 'sdweddingdirectory_vendor', [ $this, 'sdweddingdirectory_vendor' ] );

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

                            'sdweddingdirectory_vendor'    =>  sprintf( '[sdweddingdirectory_vendor %1$s][/sdweddingdirectory_vendor]', 

                                                            parent:: _shortcode_atts( self:: default_args() )
                                                        )
                        ] );
            } );
        }

        /**
         *  Search Box Start
         *  ----------------
         */
        public static function sdweddingdirectory_vendor( $atts, $content = null ){

            /**
             *  Extract Attr Data
             *  -----------------
             */
            extract( shortcode_atts( self:: default_args(), $atts, esc_attr( __FUNCTION__ ) ) );

            /**
             *  Collection
             *  ----------
             */
            $collection         =   '';

            /**
             *  Current Page
             *  ------------
             */
            $paged              =       get_query_var( 'paged' )

                                ?       absint( get_query_var( 'paged' ) )

                                :       absint( '1' );

            /**
             *  Create Query
             *  ------------
             */
            $query              =       new WP_Query( array_merge(

                                            /**
                                             *  1. Default Args
                                             *  ---------------
                                             */
                                            [   'post_type'         =>  esc_attr( 'vendor' ),

                                                'post_status'       =>  esc_attr( 'publish' ),

                                                'paged'             =>  $paged
                                            ],

                                            /**
                                             *  2. Have Extra Args ?
                                             *  --------------------
                                             */
                                            parent:: _is_array( preg_split ("/\,/", $post_ids ) ) && ! empty( $post_ids )

                                            ?   [   'post__in'          =>  preg_split ("/\,/", $post_ids )     ]

                                            :   [],

                                            /**
                                             *  3. Have Post Per Page ?
                                             *  -----------------------
                                             */
                                            ! empty( $posts_per_page )

                                            ?   [   'posts_per_page'          =>  absint( $posts_per_page )    ]

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

                                            :   []

                                        ) );

            /**
             *  Have venue post data ?
             *  ------------------------
             */
            if ( $query->have_posts() ){

                /**
                 *  One by one pass venue post id
                 *  -------------------------------
                 */
                while ( $query->have_posts() ) {  $query->the_post();

                    /** 
                     *  Style
                     *  -----
                     */
                    $collection    .=       self:: _shortcode_style_start( $style );

                    /**
                     *  Post Data
                     *  ---------
                     */
                    $collection    .=       apply_filters( 'sdweddingdirectory/vendor/post', [

                                                'layout'    =>  absint( $layout ),

                                                'post_id'   =>  absint( get_the_ID() )

                                            ] );
                    /** 
                     *  Style
                     *  -----
                     */
                    $collection    .=       self:: _shortcode_style_end( $style );
                }

                /**
                 *  Have Pagination ?
                 *  -----------------
                 */
                if( absint( $query->max_num_pages ) >= absint( '2' ) && $pagination  ==  esc_attr( 'true' ) ){

                    /**
                     *  Create Pagination
                     *  -----------------
                     */
                    $collection    .=       apply_filters( 'sdweddingdirectory/pagination', [

                                                'numpages'      =>  absint( $query->max_num_pages ),

                                                'paged'         =>  absint( $paged )

                                            ] );
                }

                /**
                 *  Reset Shortcode Venue Query
                 *  -----------------------------
                 */
                if( isset( $query ) ){

                    wp_reset_postdata();
                }
            }

            /**
             *  Return Post Data
             *  ----------------
             */
            return      $collection;
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

                                '[sdweddingdirectory_vendor 

                                    layout="%1$s" post_ids="%2$s" posts_per_page="%3$s" style="%4$s" 

                                    orderby="%5$s" order="%6$s" pagination="%7$s"]

                                [/sdweddingdirectory_vendor]',

                                /**
                                 *  1. Layout ?
                                 *  -----------
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
                                 *  3. Post Per Page ?
                                 *  ------------------
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
                                esc_attr( $pagination )
                            ) 
                        );
            }
        }
    }

    /**
     *  ----------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Vendor Post ]
     *  ----------------------------------------
     */
    SDWeddingDirectory_Shortcode_Vendor_Post::get_instance();
}