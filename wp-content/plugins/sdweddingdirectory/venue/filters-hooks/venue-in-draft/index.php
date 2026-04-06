<?php
/**
 *  SDWeddingDirectory - Venue in Draft Mode
 *  ----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Status_Draft_Mode' ) && class_exists( 'SDWeddingDirectory_Vendor_Venue_Filters' ) ){

    /**
     *  SDWeddingDirectory - Venue in Draft Mode
     *  ----------------------------------
     */
    class SDWeddingDirectory_Venue_Status_Draft_Mode extends SDWeddingDirectory_Vendor_Venue_Filters{

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
             *  When Vendor Payment Received
             *  ----------------------------
             */
            add_action( 'sdweddingdirectory/user/payment-received', [ $this, 'auto_pay_without_changes_post' ], absint( '50' ), absint( '1' ) );

            /**
             *  Expired Venue to Publish
             *  --------------------------
             */
            add_action( 'sdweddingdirectory/author-venue/draft', [  $this, 'venue_in_draft_mode' ], absint( '10' ), absint( '1' )  );
        }

        /**
         *  After Payment Draft Venue Status change with Publish
         *  ------------------------------------------------------
         */
        public static function venue_in_draft_mode( $args = [] ){

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

                    'author_id'     =>      absint( '0' )

                ] ) );

                /**
                 *  Make sure author id not empty!
                 *  ------------------------------
                 */
                if( empty( $author_id ) ){

                    return;
                }

                /**
                 *  WP_Query
                 *  --------
                 */
                $item           =       new WP_Query( [

                                                'post_type'         =>  esc_attr( 'venue' ),

                                                'post_status'       =>  array( 'publish' ),

                                                'posts_per_page'    =>  -1,

                                                'author'            =>  absint( $author_id )
                                        ] );


                /**
                 *  Query Found Post ?
                 *  ------------------
                 */
                if( $item->have_posts() ){

                    /**
                     *  In Loop
                     *  -------
                     */
                    while ( $item->have_posts() ){  $item->the_post();

                        /**
                         *  Post ID
                         *  -------
                         */
                        $post_id                =       absint( get_the_ID() );

                        /**
                         *  Publish venue
                         *  ---------------
                         */
                        wp_update_post( [

                            'ID'                    =>      absint( $post_id ),

                            'post_status'           =>      esc_attr( 'draft' ),

                            'meta_input'            =>      [ 

                                'venue_badge'     =>      ''
                            ]

                        ] );
                    }

                    /**
                     *  Rest WP_Query
                     *  -------------
                     */
                    wp_reset_postdata();
                }
            }
        }

        /**
         *  After Payment Draft Venue Status change with Publish
         *  ------------------------------------------------------
         */
        public static function auto_pay_without_changes_post( $args = [] ){

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

                    'author_id'             =>      absint( '0' ),

                    'venue_status'        =>      true,

                    'badge_empty'           =>      true,

                ] ) );

                /**
                 *  Make sure author id not empty!
                 *  ------------------------------
                 */
                if( empty( $author_id ) ){

                    return;
                }

                /**
                 *  WP_Query
                 *  --------
                 */
                $item           =       new WP_Query( [

                                                'post_type'         =>  esc_attr( 'venue' ),

                                                'post_status'       =>  array( 'publish', 'pending', 'draft', 'trash' ),

                                                'posts_per_page'    =>  -1,

                                                'author'            =>  absint( $author_id )
                                        ] );


                /**
                 *  Query Found Post ?
                 *  ------------------
                 */
                if( $item->have_posts() ){

                    /**
                     *  In Loop
                     *  -------
                     */
                    while ( $item->have_posts() ){  $item->the_post();

                        /**
                         *  Post ID
                         *  -------
                         */
                        $post_id                =       absint( get_the_ID() );

                        /**
                         *  Venue Status Update ?
                         *  -----------------------
                         */
                        if( $venue_status ){

                            wp_update_post( [

                                'ID'                    =>      absint( $post_id ),

                                'post_status'           =>      esc_attr( 'draft' ),

                            ] );
                        }

                        /**
                         *  Badge Removed
                         *  -------------
                         */
                        if( $badge_empty ){

                            get_post_meta( $post_id, sanitize_key( 'venue_badge' ), '' );
                        }
                    }

                    /**
                     *  Rest WP_Query
                     *  -------------
                     */
                    wp_reset_postdata();
                }
            }
        }

    } /* class end **/

    /**
     *  SDWeddingDirectory - Venue in Draft Mode
     *  ----------------------------------
     */
    SDWeddingDirectory_Venue_Status_Draft_Mode:: get_instance();
}