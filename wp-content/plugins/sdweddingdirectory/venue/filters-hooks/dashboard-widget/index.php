<?php
/**
 *  SDWeddingDirectory - Venue Filter & Hooks
 *  -----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Venue_Widget_Filters' ) && class_exists( 'SDWeddingDirectory_Vendor_Venue_Filters' ) ){

    /**
     *  SDWeddingDirectory - Venue Filter & Hooks
     *  -----------------------------------
     */
    class SDWeddingDirectory_Vendor_Venue_Widget_Filters extends SDWeddingDirectory_Vendor_Venue_Filters{

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
             *  Dashboard Widget
             *  ----------------
             */
            add_action( 'sdweddingdirectory_vendor_overview_widget', [ $this, 'get_total_publish_venue' ], absint( '10' ) );

            /**
             *  Dashboard Full Width Widget
             *  ---------------------------
             */
            add_action( 'sdweddingdirectory_vendor_full_widget', [ $this, 'sdweddingdirectory_vendor_full_venue_widget' ], absint( '10' ) );
        }

        /**
         *  Full Width Widget
         *  -----------------
         */
        public static function sdweddingdirectory_vendor_full_venue_widget(){

            $args = array(

                'post_type'         =>  esc_attr( 'venue' ),

                'post_status'       =>  array( 'publish', 'pending', 'draft', 'trash' ),

                'posts_per_page'    =>  absint( '3' ),

                'orderby'           => 'menu_order ID',

                'order'             => esc_attr( 'post_date' ),

                'author'            => absint( parent:: author_id() )
            );

            $have_venue = new WP_Query( $args );

            if( $have_venue->found_posts >= absint( '1' ) ){

                ?>
                <div class="card-shadow">

                    <div class="card-shadow-header">

                        <div class="dashboard-head">
                            <?php

                                printf(    '<h3>%1$s</h3>

                                            <div class="links">

                                                <a href="%2$s">%3$s <i class="fa fa-angle-right"></i></a>

                                            </div>',

                                            /**
                                             *  1. Translation Ready String
                                             *  ---------------------------
                                             */
                                            esc_attr__( 'Your Venue', 'sdweddingdirectory-venue' ),

                                            /**
                                             *  2. Venue Page Link
                                             *  --------------------
                                             */
                                            apply_filters( 'sdweddingdirectory/vendor-menu/page-link', esc_attr( 'vendor-venue' ) ),

                                            /**
                                             *  3. Translation Ready String
                                             *  ---------------------------
                                             */
                                            esc_attr__( 'View All', 'sdweddingdirectory-venue' )
                                );
                            ?>
                        </div>

                    </div>

                    <div class="card-shadow-body p-0">

                        <?php parent:: display_venue( $args ); ?>

                    </div>
                    
                </div>
                <?php
            }
        }

        /**
         *  Widget
         *  ------
         */
        public static function get_total_publish_venue(){

            ?>
            <div class="col-lg-4 col-md-6">
                <div class="card-shadow">
                    <div class="card-shadow-body">
                        <div class="couple-info vendor-stats">
                            <div class="couple-status-item">
                                <div class="counter">
                                    <?php

                                        /**
                                         *  Current Login Vendor Total Publish Venue
                                         *  ------------------------------------------
                                         */
                                        print   absint( parent:: _count_venue( array(

                                                    'publish', 'pending', 'trash', 'draft'

                                                ) ) );
                                    ?>
                                </div>
                                <div class="text">
                                    <div class="div">
                                        <strong>
                                        <?php

                                            esc_attr_e( 'Listed Item', 'sdweddingdirectory-venue' );

                                        ?>
                                        </strong>
                                    </div>
                                    <?php
 
                                        /**
                                         *  View All Venue Page Link
                                         *  --------------------------
                                         */
                                        printf( '<a href="%1$s" class="btn-veiw-all">%2$s</a>',

                                            /**
                                             *  1. My Venue Page Link
                                             *  -----------------------
                                             */
                                            apply_filters( 'sdweddingdirectory/vendor-menu/page-link', esc_attr( 'vendor-venue' ) ),
                                            
                                            /**
                                             *  2. Translation String
                                             *  ---------------------
                                             */
                                            esc_attr__( 'View All', 'sdweddingdirectory-venue' )

                                        );
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Vendor_Venue_Widget_Filters:: get_instance();
}