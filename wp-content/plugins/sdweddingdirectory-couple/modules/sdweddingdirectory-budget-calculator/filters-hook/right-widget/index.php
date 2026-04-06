<?php
/**
 *  SDWeddingDirectory - Budget Page Filters
 *  --------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Budget_Right_Widget_Filters' ) && class_exists( 'SDWeddingDirectory_Budget_Filters' ) ){

    /**
     *  SDWeddingDirectory - Budget Page Filters
     *  --------------------------------
     */
    class SDWeddingDirectory_Budget_Right_Widget_Filters extends SDWeddingDirectory_Budget_Filters{

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
             *  Dashboard Sidebar Widget
             *  ------------------------
             */
            add_action( 'sdweddingdirectory/couple/dashboard/widget/right-side', [ $this, 'widget' ], absint( '30' ) );
        }

        /**
         *  Dashboard Sidebar Widget
         *  ------------------------
         */
        public static function widget(){

            ?>
            <div class="card-shadow">
                <div class="card-shadow-header">
                    <div class="dashboard-head">
                        <?php

                          /**
                           *  Budget Wiget Summery Title + Redirect link
                           *  ------------------------------------------
                           */
                          printf(  '<h3>%1$s</h3><div class="links"><a href="%2$s">%3$s <i class="fa fa-angle-right"></i></a></div>',

                                    /**
                                     *  1. String Translation
                                     *  ---------------------
                                     */
                                    esc_attr__( 'Budget', 'sdweddingdirectory-budget-calculator' ),

                                    /**
                                     *  2. Budget Page Link
                                     *  -------------------
                                     */
                                    apply_filters( 'sdweddingdirectory/couple-menu/page-link', esc_attr( 'budget-calculator' ) ),

                                    /**
                                     *  3. String Translation
                                     *  ---------------------
                                     */
                                    esc_attr__( 'View Budget', 'sdweddingdirectory-budget-calculator' )
                          );

                        ?>
                    </div>
                </div>

                <div class="card-shadow-body">
                    <div class="budget-estimation">
                        <div class="d-flex w-100">
                            <div class="etimated-cost">
                                <div class="icon"><i class="sdweddingdirectory-saving-money"></i></div> 
                                <p class="cost-price"><?php 

                                    echo sdweddingdirectory_pricing_possition( absint( parent:: get_data( 'sdweddingdirectory_budget_amount' ) ) );

                                ?></p>
                                <div><?php esc_attr_e( 'Estimated cost', 'sdweddingdirectory-budget-calculator' ); ?></div>
                            </div>
                            <div class="etimated-cost">
                                <div class="icon"><i class="sdweddingdirectory-money-stack"></i></div>
                                <p class="cost-price final"><?php 

                                    echo sdweddingdirectory_pricing_possition( absint( parent:: get_budget_counter( 'actual_amount' ) ) ); 

                                ?></p>
                                <div><?php esc_attr_e( 'Final cost', 'sdweddingdirectory-budget-calculator' ); ?></div>
                            </div>
                        </div>
                        
                        <?php

                          /**
                           *  Budget Page Redirection with translation string
                           *  -----------------------------------------------
                           */
                          printf(  '<a class="btn btn-outline-default btn-rounded" href="%1$s">%2$s</a>',

                                    /**
                                     *  1. Budget Page Link
                                     *  -------------------
                                     */
                                    apply_filters( 'sdweddingdirectory/couple-menu/page-link', esc_attr( 'budget-calculator' ) ),

                                    /**
                                     *  2. String Translation
                                     *  ---------------------
                                     */
                                    esc_attr__( 'Manage expenses', 'sdweddingdirectory-budget-calculator' )
                          );

                        ?>
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
    SDWeddingDirectory_Budget_Right_Widget_Filters:: get_instance();
}