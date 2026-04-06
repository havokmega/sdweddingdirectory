<?php
/**
 *  SDWeddingDirectory - Vendor Right Widget: Review Summary
 *  -------------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Singular_Right_Side_Widget_Review_Summary' ) && class_exists( 'SDWeddingDirectory_Vendor_Singular_Right_Side_Widget' ) ){

    /**
     *  SDWeddingDirectory - Vendor Right Widget: Review Summary
     *  -------------------------------------------------------
     */
    class SDWeddingDirectory_Vendor_Singular_Right_Side_Widget_Review_Summary extends SDWeddingDirectory_Vendor_Singular_Right_Side_Widget{

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
             *  Review Summary
             *  -------------
             */
            // Disabled — duplicate review summary removed; full reviews widget in main content is kept.
            // add_action( 'sdweddingdirectory/vendor/singular/right-side/widget', [ $this, 'widget' ], absint( '15' ), absint( '1' ) );
        }

        /**
         *  Review Summary
         *  -------------
         */
        public static function widget( $args = [] ){

            if( parent:: _is_array( $args ) ){

                extract( wp_parse_args( $args, [

                    'post_id'       =>      absint( '0' )

                ] ) );

                if( empty( $post_id ) ){

                    return;
                }

                $count_rating = apply_filters( 'sdweddingdirectory/rating/found', '', [

                    'venue_id'  =>  absint( $post_id )

                ] );

                if( empty( $count_rating ) ){

                    return;
                }

                $average = apply_filters( 'sdweddingdirectory/rating/average', '', [

                    'venue_id'  =>  absint( $post_id )

                ] );

                ?>
                <div class="widget">

                    <h3 class="widget-title"><?php esc_attr_e( 'Reviews', 'sdweddingdirectory' ); ?></h3>

                    <div class="d-flex align-items-center justify-content-between">

                        <span class="fw-bold"><i class="fa fa-star"></i> <?php echo esc_html( $average ); ?></span>

                        <a class="btn-link btn-link-secondary" href="#section_venue_ratings">
                            <?php printf( esc_attr__( '%1$s Reviews', 'sdweddingdirectory' ), absint( $count_rating ) ); ?>
                        </a>

                    </div>

                </div>
                <?php
            }
        }
    }

    /**
     *  SDWeddingDirectory - Vendor Right Widget: Review Summary
     *  -------------------------------------------------------
     */
    SDWeddingDirectory_Vendor_Singular_Right_Side_Widget_Review_Summary::get_instance();
}
