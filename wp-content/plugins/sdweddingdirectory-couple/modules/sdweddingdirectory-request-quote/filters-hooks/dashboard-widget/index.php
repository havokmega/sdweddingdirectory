<?php
/**
 *  Filter & Hooks
 *  --------------
 */
if( ! class_exists( 'SDWeddingDirectory_Request_Quote_Dashboard_Widget_Filters' ) && class_exists( 'SDWeddingDirectory_Request_Quote_Filters' ) ){

    /**
     *  Filter & Hooks
     *  --------------
     */
    class SDWeddingDirectory_Request_Quote_Dashboard_Widget_Filters extends SDWeddingDirectory_Request_Quote_Filters{

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
             *  Dashboard Overview Widget
             *  -------------------------
             */
            add_action( 'sdweddingdirectory_vendor_overview_widget', [ $this, 'get_total_request_quote' ], absint( '10' ) );
        }

        /**
         *  2. Dashboard Overview Widget : Request Quote
         *  --------------------------------------------
         */
        public static function get_total_request_quote(){

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
                                        absint( parent:: get_request_quote_counter() ),

                                        /**
                                         *  2. Translation String
                                         *  ---------------------
                                         */
                                        esc_attr__( 'Request quote', 'sdweddingdirectory-request-quote' ),

                                        /**
                                         *  3. Link for Request Quote Page
                                         *  ------------------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/vendor-menu/page-link', esc_attr( 'vendor-request-quote' ) ),

                                        /**
                                         *  4. Translation String
                                         *  ---------------------
                                         */
                                        esc_attr__( 'View All', 'sdweddingdirectory-request-quote' )
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
    SDWeddingDirectory_Request_Quote_Dashboard_Widget_Filters:: get_instance();
}