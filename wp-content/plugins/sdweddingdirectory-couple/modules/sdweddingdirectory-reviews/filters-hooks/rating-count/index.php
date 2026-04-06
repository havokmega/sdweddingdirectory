<?php
/**
 *  SDWeddingDirectory - Count the Rating Post
 *  ----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Reviews_Count_Filters' ) && class_exists( 'SDWeddingDirectory_Review_Filters' ) ){

    /**
     *  SDWeddingDirectory - Count the Rating Post
     *  ----------------------------------
     */
    class SDWeddingDirectory_Reviews_Count_Filters extends SDWeddingDirectory_Review_Filters{

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
            add_filter( 'sdweddingdirectory/rating/found', [ $this, 'found_post' ], absint( '10' ), absint( '2' ) );
        }

        /**
         *  Venue Reviews Comment
         *  -----------------------
         */
        public static function found_post( $handler = '', $args = [] ){

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

                    'handler'           =>      absint( '0' ),

                    'meta_query'        =>      []

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
                 *  Handler
                 *  -------
                 */
                $handler                =    absint( $query->found_posts );

                /**
                 *  Reset Query
                 *  -----------
                 */
                wp_reset_postdata();

                /**
                 *  Found Post
                 *  ----------
                 */
                return          absint( $handler );
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Reviews_Count_Filters:: get_instance();
}