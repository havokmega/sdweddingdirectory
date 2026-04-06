<?php
/**
 *  SDWeddingDirectory Couple Budget Category Form
 *  --------------------------------------
 */

if( ! class_exists( 'SDWeddingDirectory_Budget_Amount_Form' ) && class_exists( 'SDWeddingDirectory_Dashboard_Budget' ) ){

    /**
     *  SDWeddingDirectory Requests
     *  -------------------
     */
    class SDWeddingDirectory_Budget_Amount_Form extends SDWeddingDirectory_Dashboard_Budget{

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
             *  Is Budget Calculator Page
             *  -------------------------
             */
            if(  parent:: dashboard_page_set( 'budget-calculator' ) ) {

                /**
                 *  1. Add New Budget Category Task Form
                 *  ------------------------------------
                 */            
                add_action( 'wp_footer', [$this, 'budget_amount_form_markup'] );
            }
        }

        /**
         *  1. Load Create Todo Form
         *  ------------------------
         */
        public static function budget_amount_form_markup(){

            ?>

            <div id="sdweddingdirectory_budget_amount_sidepanel" class="sliding-panel">

                <div class="card-shadow-header mb-0">
                    <div class="dashboard-head">

                        <h3><?php esc_attr_e( 'Edit the Estimated Cost', 'sdweddingdirectory-budget-calculator' ); ?></h3>

                    </div>
                </div>

                <div class="card-shadow-body">

                    <form id="sdweddingdirectory_budget_amount_form" method="post" autocomplete="off" >

                        <div class="row"><?php self:: sdweddingdirectory_budget_amount_content(); ?></div>

                    </form>

                </div>

            </div>

            <?php
        }

        /**
         *  1. Load Create Todo Form
         *  ------------------------
         */
        public static function sdweddingdirectory_budget_amount_content(){

            printf('<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="mb-3">
                            <label class="control-label" for="%1$s">%2$s</label>
                            <input autocomplete="off" id="%1$s" name="%1$s" type="number" placeholder="%3$s" class="form-control form-light" required="">
                        </div>
                    </div>',

                    // 1
                    esc_attr( 'sdweddingdirectory_budget_amount' ),

                    // 2
                    esc_attr__( 'Estimate Cost', 'sdweddingdirectory-budget-calculator' ),

                    // 3
                    esc_attr__( 'Estimate Cost', 'sdweddingdirectory-budget-calculator' )
            );

            printf('<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="mb-3">
                            <button type="submit" id="sdweddingdirectory_estimate_budget_amount_btn" class="btn btn-default">%1$s</button>
                            %2$s
                        </div>
                    </div>',

                    // 1
                    esc_attr__( 'Save', 'sdweddingdirectory-budget-calculator' ),

                    // 2
                    wp_nonce_field( 'budget_amount_security', 'budget_amount_security', true, false )
            );
        }

    } /* class end **/

    /**
     *  Kicking this off by calling 'get_instance()' method
     *  ---------------------------------------------------
     */
    SDWeddingDirectory_Budget_Amount_Form:: get_instance();
}