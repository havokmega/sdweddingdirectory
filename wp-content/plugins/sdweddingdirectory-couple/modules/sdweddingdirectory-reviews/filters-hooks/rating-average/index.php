<?php
/**
 *  SDWeddingDirectory - Average the Rating Post
 *  ------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Reviews_Average_Filters' ) && class_exists( 'SDWeddingDirectory_Review_Filters' ) ){

    /**
     *  SDWeddingDirectory - Average the Rating Post
     *  ------------------------------------
     */
    class SDWeddingDirectory_Reviews_Average_Filters extends SDWeddingDirectory_Review_Filters{

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
             *   Venue Rating Filter
             *   ---------------------
             */
            add_filter( 'sdweddingdirectory/rating/average', [ $this, 'post_average' ], absint( '10' ), absint( '2' ) );
        }

        /**
         *  Venue Reviews Comment
         *  -----------------------
         */
        public static function post_average( $handler = '', $args = [] ){

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

                    'venue_id'        =>      absint( '0' ),

                    'vendor_id'         =>      absint( '0' ),

                    'meta_query'        =>      [],

                    'handler'           =>      absint( '0' ),

                    'meta_key'          =>      esc_attr( 'average_rating' ),

                    'before'            =>      '',

                    'after'             =>      '',

                    'icon'              =>      ''

                ] ) );

                /**
                 *  Query have venue ID ?
                 *  -----------------------
                 */
                if( ! empty( $venue_id ) ){

                    /**
                     *  Venue ID
                     *  ----------
                     */
                    $meta_query[]   =   [

                        'key'           =>      esc_attr( 'venue_id' ),

                        'type'          =>      esc_attr( 'numeric' ),

                        'compare'       =>      esc_attr( '=' ),

                        'value'         =>      absint( $venue_id ),
                    ];
                }

                /**
                 *  Query have vendor ID ?
                 *  ----------------------
                 */
                if( ! empty( $vendor_id ) ){

                    /**
                     *  Author ID
                     *  ---------
                     */
                    $meta_query[]   =   [

                        'key'           =>      esc_attr( 'vendor_id' ),

                        'type'          =>      esc_attr( 'numeric' ),

                        'compare'       =>      esc_attr( '=' ),

                        'value'         =>      absint( $vendor_id ),
                    ];
                }

                /**
                 *  Have POST ?
                 *  -----------
                 */
                $query              =       new WP_Query( array_merge(

                                                /**
                                                 *  1. Default Args
                                                 *  ---------------
                                                 */
                                                array(

                                                    'post_type'         =>      esc_attr( 'venue-review' ),

                                                    'post_status'       =>      array( 'publish' ),

                                                    'posts_per_page'    =>      -1,
                                                ),

                                                /**
                                                 *  2. Have Meta Query ?
                                                 *  --------------------
                                                 */
                                                parent:: _is_array( $meta_query )

                                                ?   [   'meta_query'  => [ 'relation'  => 'AND', $meta_query ]  ]

                                                :   []

                                            ) );

                /**
                 *  Found Post
                 *  ----------
                 */
                $post_found         =       absint( $query->found_posts );

                /**
                 *  Have Query Post at least 1 ?
                 *  ----------------------------
                 */
                if( $post_found >= absint( '1' ) ){

                    /**
                     *  Have Post ?
                     *  -----------
                     */
                    if ( $query->have_posts() ){

                        while ( $query->have_posts() ) {  $query->the_post();

                            /**
                             *  1. Get ID
                             *  ---------
                             */
                            $post_id        =       absint( get_the_ID() );

                            /**
                             *  Collect Average Group
                             *  ---------------------
                             */
                            if( $meta_key == esc_attr( 'average_rating' ) ){

                                /**
                                 *  Get Avarage Rating
                                 *  ------------------
                                 */
                                $collect     =   self:: get_avarage_rating( [ 'post_id'  =>  $post_id  ] );
                            }

                            /**
                             *  Specific Meta Key Target Query
                             *  ------------------------------
                             */
                            else{

                                /**
                                 *  Update data
                                 *  -----------
                                 */
                                $collect     =   get_post_meta( $post_id, sanitize_key( $meta_key ), true );
                            }

                            /**
                             *  Count Review
                             *  ------------
                             */
                            if( is_numeric( $collect ) ){

                                $handler  +=  $collect;
                            }
                        }
                    }

                    /**
                     *  Reset Query
                     *  -----------
                     */
                    wp_reset_postdata();

                    /**
                     *  Make sure handler not empty!
                     *  ----------------------------
                     */
                    if( empty( $handler ) ){

                        return      absint( '0' );
                    }

                    /**
                     *  Return Average Rating
                     *  ---------------------
                     */
                    return      $before . $icon . number_format_i18n(  ( $handler / $post_found ), '1' ) . $after;
                }

                /**
                 *  Make sure handler not empty!
                 *  ----------------------------
                 */
                if( empty( $handler ) ){

                    return      absint( '0' );
                }
            }
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

                    'data'                      =>      esc_attr( 'average_rating' )

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
                if( $data == esc_attr( 'quality_rating' ) ){

                    return      absint( $quality_rating );
                }

                /**
                 *  Facilities Rateing
                 *  ------------------
                 */
                elseif( $data == esc_attr( 'facilities_rating' ) ){

                    return      absint( $facilities_rating );
                }

                /**
                 *  Staff Rateing
                 *  -------------
                 */
                elseif( $data == esc_attr( 'staff_raring' ) ){

                    return      absint( $staff_raring );
                }

                /**
                 *  Flexibility Rating
                 *  ------------------
                 */
                elseif( $data == esc_attr( 'flexibility_rating' ) ){

                    return      absint( $flexibility_rating );
                }

                /**
                 *  Value of Money ?
                 *  ----------------
                 */
                elseif( $data == esc_attr( 'value_of_money_rating' ) ){

                    return      absint( $value_of_money_rating );
                }

                /**
                 *  Avarage Rating [ Default Return ]
                 *  =================================
                 */
                elseif( $data == esc_attr( 'average_rating' ) ){

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

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Reviews_Average_Filters:: get_instance();
}