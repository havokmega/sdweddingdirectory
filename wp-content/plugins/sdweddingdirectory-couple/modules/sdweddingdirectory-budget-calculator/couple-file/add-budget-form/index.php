<?php
/**
 *  SDWeddingDirectory Couple Budget Category Form
 *  --------------------------------------
 */

if( ! class_exists( 'SDWeddingDirectory_Create_Budget_Category_Form' ) && class_exists( 'SDWeddingDirectory_Dashboard_Budget' ) ){

    /**
     *  SDWeddingDirectory Requests
     *  -------------------
     */
    class SDWeddingDirectory_Create_Budget_Category_Form extends SDWeddingDirectory_Dashboard_Budget{

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
                add_action( 'wp_footer', [$this, 'sdweddingdirectory_add_new_budget_category_form_markup'] );
            }
        }

        /**
         *  1. Load Create Todo Form
         *  ------------------------
         */
        public static function sdweddingdirectory_add_new_budget_category_form_markup(){

            ?>

            <div id="sdweddingdirectory_add_budget_category_sidepanel" class="sliding-panel">

                <div class="card-shadow-header mb-0">
                    <div class="dashboard-head">

                        <h3><?php esc_attr_e( 'Add New category', 'sdweddingdirectory-budget-calculator' ); ?></h3>

                    </div>
                </div>

                <div class="card-shadow-body">

                    <form id="sdweddingdirectory_add_budget_category_form" method="post" autocomplete="off" >

                        <div class="row"><?php self:: sdweddingdirectory_create_budget_task_content(); ?></div>

                    </form>

                </div>

            </div>

            <?php
        }

        /**
         *  1. Load Create Todo Form
         *  ------------------------
         */
        public static function sdweddingdirectory_create_budget_task_content(){

            printf('<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="mb-3">
                            <label class="control-label" for="%1$s">%2$s</label>
                            <input autocomplete="off" id="%1$s" name="%1$s" type="text" placeholder="%3$s" class="form-control form-light" required="">
                        </div>
                    </div>',

                    // 1
                    esc_attr( 'budget_category_name' ),

                    // 2
                    esc_attr__( 'Budget Category Name', 'sdweddingdirectory-budget-calculator' ),

                    // 3
                    esc_attr__( 'Budget Category Name', 'sdweddingdirectory-budget-calculator' )
            );

            printf('<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="mb-3">
                            <label class="control-label" for="%1$s">%2$s</label>
                            <input autocomplete="off" id="%1$s" name="%1$s" type="text" placeholder="%3$s" class="form-control form-light">
                        </div>
                    </div>',

                    // 1
                    esc_attr( 'budget_category_overview' ),

                    // 2
                    esc_attr__( 'Write Notes', 'sdweddingdirectory-budget-calculator' ),

                    // 3
                    esc_attr__( 'Write Notes', 'sdweddingdirectory-budget-calculator' )
            );

            printf('<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="mb-3">
                            <label class="control-label" for="%1$s">%2$s</label>
                            <select id="%1$s" name="%1$s" class="sdweddingdirectory-light-select">%3$s</select>
                        </div>
                    </div>',

                    // 1
                    esc_attr( 'budget_category_icon' ),

                    // 2
                    esc_attr__( 'Select Category Icon', 'sdweddingdirectory-budget-calculator' ),

                    // 3
                    self:: get_icon_options()
            );

            printf('<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="mb-3">
                            <button type="submit" id="add_budget_category_btn" class="btn btn-default">%1$s</button>
                            %2$s
                        </div>
                    </div>',

                    // 1
                    esc_attr__( 'Add Budget Category', 'sdweddingdirectory-budget-calculator' ),

                    // 2
                    wp_nonce_field( 'add_budget_categor_security', 'add_budget_categor_security', true, false )
            );
        }

        public static function get_icon_options(){

            $_icons = apply_filters( 'sdweddingdirectory_icons_set_for_ot', [] );

            $_option = '';

            if( SDWeddingDirectory_Loader:: _is_array( $_icons ) ){

                foreach ( $_icons as $key => $value) {
                    
                    $_option .= 

                    sprintf( '<option value="%1$s">%1$s</option>', 

                        // 1
                        esc_attr( $value[ 'value' ] )
                    );
                }
            }

            return $_option;
        }

    } /* class end **/

    /**
     *  Kicking this off by calling 'get_instance()' method
     *  ---------------------------------------------------
     */
    SDWeddingDirectory_Create_Budget_Category_Form:: get_instance();
}