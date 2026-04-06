<?php
/**
 *  Filter & Hooks
 *  --------------
 */
if( ! class_exists( 'SDWeddingDirectory_Review_Filters' ) && class_exists( 'SDWeddingDirectory_Reviews_Database' ) ){

    /**
     *  Filter & Hooks
     *  --------------
     */
    class SDWeddingDirectory_Review_Filters extends SDWeddingDirectory_Reviews_Database{

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
             *  Load one by one shortcode file
             *  ------------------------------
             */
            foreach ( glob( plugin_dir_path( __FILE__ ) . '/*/index.php' ) as $file ) {
           
                require_once $file;
            }
        }

        /**
         *  Dashboard Overview Widget : Request Quote
         *  -----------------------------------------
         */
        public static function get_total_review(){

            ?>
            <div class="col-lg-4 col-md-6">
                <div class="card-shadow">
                    <div class="card-shadow-body">
                        <div class="couple-info vendor-stats">
                            <?php

                                /**
                                 *  1. Get Number of Request for this vendor
                                 *  ----------------------------------------
                                 */
                                printf('<div class="couple-status-item">
                                            <div class="counter">%1$s</div>
                                            <div class="text">
                                                <div class="div"><strong>%2$s</strong></div>
                                                <a href="%3$s" class="btn-veiw-all">%4$s</a>
                                            </div>
                                        </div>',

                                        /**
                                         *  1. Get Number of Request Quote for this vendor
                                         *  ----------------------------------------------
                                         */
                                        absint( parent:: get_total_review_for_vendor() ),

                                        /**
                                         *  2. Translation String
                                         *  ---------------------
                                         */
                                        esc_attr__( 'Your Review', 'sdweddingdirectory-reviews' ),

                                        /**
                                         *  3. Link for Request Quote Page
                                         *  ------------------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/vendor-menu/page-link', esc_attr( 'vendor-reviews' ) ),

                                        /**
                                         *  4. Translation String
                                         *  ---------------------
                                         */
                                        esc_attr__( 'View All', 'sdweddingdirectory-reviews' )
                                );
                            ?>
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
    SDWeddingDirectory_Review_Filters:: get_instance();
}