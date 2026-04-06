<?php
/**
 *  SDWeddingDirectory - Venue Filter & Hooks
 *  -----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Singular_Right_Side_Widget_Featured_Venue' ) && class_exists( 'SDWeddingDirectory_Venue_Singular_Right_Side_Widget' ) ){

    /**
     *  SDWeddingDirectory - Venue Filter & Hooks
     *  -----------------------------------
     */
    class SDWeddingDirectory_Venue_Singular_Right_Side_Widget_Featured_Venue extends SDWeddingDirectory_Venue_Singular_Right_Side_Widget {

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
             *  Featured - Venue
             *  ------------------
             */
            add_action( 'sdweddingdirectory/venue/singular/right-side/widget', [ $this, 'widget' ], absint( '50' ), absint( '1' ) );
        }

        /**
         *  Featured - Venue
         *  ------------------
         */
        public static function widget( $args = [] ){

            /**
             *  Have Data ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'counter'           =>          absint( '4' ) 

                ] ) );

                /**
                 *  Post Category
                 *  -------------
                 */
                $post_cat       =   apply_filters( 'sdweddingdirectory/post-tax', [

                                        'post_id'       =>      $post_id

                                    ] );

                /**
                 *  Query
                 *  -----
                 */
                $query    =     new WP_Query( array(

                                    'post_type'         =>      esc_attr( 'venue' ),

                                    'post_status'       =>      esc_attr( 'publish' ),

                                    'posts_per_page'    =>      -1,

                                    'orderby'           =>      esc_attr( 'rand' ),

                                    'post__not_in'      =>      [   $post_id    ],

                                    'meta_query'        =>      [   'relation'  => 'AND',

                                                                    [   'key'       =>  esc_attr( 'venue_badge' ),

                                                                        'type'      =>  esc_attr( 'CHAR' ),

                                                                        'compare'   =>  esc_attr( 'IN' ),

                                                                        'value'     =>  [ 'spotlight', 'featured', 'professional' ]
                                                                    ],
                                                                ],

                                    'tax_query'         =>      [   'relation'  => 'AND',

                                                                    [   'taxonomy'  =>  esc_attr( 'venue-type' ),

                                                                        'field'     =>  esc_attr( 'id' ),

                                                                        'terms'     =>  $post_cat
                                                                    ],
                                                                ]
                                ) );

                /**
                 *  Have Query
                 *  ----------
                 */
                if( $query->have_posts() ){

                    /**
                     *  Heading
                     *  -------
                     */
                    printf( '<div class="widget">

                                <h3 class="widget-title">%1$s</h3>

                                <div class="owl-carousel sdweddingdirectory-venue-widget-carousel">',

                                /**
                                 *  1. Translation Ready String
                                 *  ---------------------------
                                 */
                                esc_attr__( 'Premium Venue', 'sdweddingdirectory-venue' )
                    );

                    while( $query->have_posts() ){  $query->the_post();

                        /**
                         *  Print Carousel
                         *  --------------
                         */
                        printf( '<div class="item">%1$s</div>',

                            /**
                             *  Load Article
                             *  ------------
                             */
                            apply_filters( 'sdweddingdirectory/venue/post', [

                                'post_id'   =>  absint( get_the_ID() ),

                                'layout'    =>  absint( '1' ),

                                'column'    =>  ''

                            ] )
                        );

                        $counter--;

                        /**
                         *  Make sure counter is not 0
                         *  --------------------------
                         */
                        if( $counter == absint( '0' ) ){

                            break;
                        }
                    }

                    ?></div></div><?php

                    /**
                     *  Have Query ?
                     *  ------------
                     */
                    if( isset( $query ) ){

                        $query->wp_reset_postdata();
                    }
                }
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Venue_Singular_Right_Side_Widget_Featured_Venue:: get_instance();
}