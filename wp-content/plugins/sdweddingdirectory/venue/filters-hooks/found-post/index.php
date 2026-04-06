<?php
/**
 *  SDWeddingDirectory - Venue Filter & Hooks
 *  -----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Found_Venue_Filter' ) && class_exists( 'SDWeddingDirectory_Vendor_Venue_Filters' ) ){

    /**
     *  SDWeddingDirectory - Venue Filter & Hooks
     *  -----------------------------------
     */
    class SDWeddingDirectory_Found_Venue_Filter extends SDWeddingDirectory_Vendor_Venue_Filters{

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
             *  Post Found
             *  ----------
             */
            add_filter( 'sdweddingdirectory/vendor/venue/found', [ $this, 'post_found' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Found Post
         *  ----------
         */
        public static function post_found( $args = [] ){

            /**
             *  Have Filter
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( wp_parse_args( $args, [

                    'author_id'         =>      absint( parent:: author_id() ),

                    'badge'             =>      '',

                    'meta_query'        =>      [],

                    'post_status'       =>      [ 'publish' ]

                ] ) );

                /**
                 *  Make sure author id found
                 *  -------------------------
                 */
                if( empty( $author_id ) ){

                    return;
                }

                /**
                 *  Have Badge ?
                 *  ------------
                 */
                if( ! empty( $badge ) ){

                    /**
                     *  Venue Badge Meta Query
                     *  ------------------------
                     */
                    $meta_query[]       =   array(

                                                'key'           =>      esc_attr( 'venue_badge' ),

                                                'compare'       =>      esc_attr( '=' ),

                                                'value'         =>      $badge
                                            );
                }

                /**
                 *  Have Args ?
                 *  -----------
                 */
                $item       =       new WP_Query(  array_merge(

                                        /**
                                         *  1. Default Args
                                         *  ---------------
                                         */
                                        [
                                            'post_type'         =>      esc_attr( 'venue' ),

                                            'post_status'       =>      $post_status,

                                            'posts_per_page'    =>       -1,

                                            'author'            =>      absint( $author_id ),
                                        ],

                                        /**
                                         *  2. If Have Meta Query ?
                                         *  -----------------------
                                         */
                                        parent:: _is_array( $meta_query ) 

                                        ?   array(

                                                'meta_query'        => array(

                                                    'relation'  => 'AND',

                                                    $meta_query,
                                                )
                                            )

                                        :   [],

                                    ) );

                /**
                 *  Found Post
                 *  ----------
                 */
                return              absint( $item->found_posts );
            }
        }
        
    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Found_Venue_Filter:: get_instance();
}