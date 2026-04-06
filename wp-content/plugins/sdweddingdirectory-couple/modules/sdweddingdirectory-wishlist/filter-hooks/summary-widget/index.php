<?php
/**
 *  Filter & Hooks
 *  --------------
 */
if( ! class_exists( 'SDWeddingDirectory_WishList_Summary_Widget_Filters' ) && class_exists( 'SDWeddingDirectory_WishList_Filters' ) ){

    /**
     *  Filter & Hooks
     *  --------------
     */
    class SDWeddingDirectory_WishList_Summary_Widget_Filters extends SDWeddingDirectory_WishList_Filters{

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
             *  Dashboard Summary
             *  -----------------
             */            
            add_action( 'sdweddingdirectory/couple/dashboard/widget/summary', [ $this, 'dashboard_summary' ], absint( '10' ) );
        }

        /**
         *  2. Wishlist : Couple Hire Number Of Category Vendor
         *  ---------------------------------------------------
         */
        public static function dashboard_summary(){

            ?>
            <div class="col">

                <div class="couple-status-item">

                    <div class="counter"><?php echo absint( parent:: count_hire_category_vendor() ); ?></div>

                    <div class="text">

                        <strong>
                        <?php

                            /**
                             *  1. Showing Pending Todo
                             *  -----------------------
                             */
                            printf( esc_attr__( 'Out of %1$s', 'sdweddingdirectory-wishlist' ),

                                /**
                                 *  1. Get Pending Todo Counter
                                 *  ---------------------------
                                 */
                                absint( count( (array) SDWeddingDirectory_Taxonomy:: get_taxonomy_depth(

                                      /**
                                       *  1. Venue Category Slug
                                       *  ------------------------
                                       */
                                      esc_attr( 'venue-type' ),

                                      /**
                                       *  2. Parent
                                       *  ---------
                                       */
                                      absint( '1' )
                                ) ) )
                            );

                        ?>
                        </strong>

                        <small><?php esc_attr_e( 'Services Hired', 'sdweddingdirectory-wishlist' ); ?></small>

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
    SDWeddingDirectory_WishList_Summary_Widget_Filters:: get_instance();
}