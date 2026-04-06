<?php
/**
 *  SDWeddingDirectory - Couple Budget Calculator Tool
 *  ------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Dashboard_Budget' ) && class_exists( 'SDWeddingDirectory_Budget_Database' ) ){

    /**
     *  SDWeddingDirectory - Couple Budget Calculator Tool
     *  ------------------------------------------
     */
    class SDWeddingDirectory_Dashboard_Budget extends SDWeddingDirectory_Budget_Database{

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
             *  1. Dashboard Scripts
             *  --------------------
             */
            add_action( 'wp_enqueue_scripts', [$this, 'sdweddingdirectory_script'], absint( '40' ) );

            /**
             *  2. Dashboard Page
             *  -----------------
             */
            add_action( 'sdweddingdirectory/couple-dashboard', [$this, 'dashboard_page'], absint( '40' ), absint( '1' ) );

            /**
             *  Load one by one shortcode file
             *  ------------------------------
             */
            foreach ( glob( plugin_dir_path( __FILE__ ) . '/*/index.php' ) as $file ) {
           
                require_once $file;
            }
        }

        /**
         *  1. Dashboard Scripts
         *  --------------------
         */
        public static function sdweddingdirectory_script(){

            /**
             *  Is Budget Calculator Page
             *  -------------------------
             */
            if(  parent:: dashboard_page_set( 'budget-calculator' ) ) {

                /**
                 *  Slug
                 *  ----
                 */
                $_slug    = sanitize_title( __CLASS__ );

                /**
                *  Load Library
                *  ------------
                */
                wp_enqueue_script( 

                    /**
                     *  File Name
                     *  ---------
                     */
                    esc_attr(   $_slug   ),

                    /**
                     *  File Link
                     *  ---------
                     */
                    esc_url(    plugin_dir_url( __FILE__ )     .   'script.js'  ),

                    /**
                     *  Have Dependancy ?
                     *  -----------------
                     */
                    array( 'jquery', 'toastr', 'slide-reveal' ),

                    /**
                     *  File Version ?
                     *  --------------
                     */
                    esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ ) . 'script.js' ) ),

                    /**
                     *  Load in Footer ?
                     *  ----------------
                     */
                    true
                );

                /**
                 *  Side reveal Library request
                 *  ---------------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/slide-reveal', function( $args = [] ){

                    return  array_merge( $args, [  'calculator-calculator'   =>  true  ] );

                } );

                /**
                 *  Pie Chart Library request
                 *  -------------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/pie-chart', function( $args = [] ){

                    return  array_merge( $args, [  'calculator-calculator'   =>  true  ] );

                } );
            }
        }

        /**
         *  2. Dashboard Page
         *  -----------------
         */
        public static function dashboard_page( $args = [] ){

            /**
             *  Have Args
             *  ---------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args,[

                    'layout'        =>      absint( '1' ),

                    'post_id'       =>      '',

                    'page'          =>      ''

                ] ) );

                /**
                 *  Make sure this page is couple dashboard page
                 *  --------------------------------------------
                 */
                if( ! empty( $page ) && $page == esc_attr( 'budget-calculator' )  ){

                    ?><div class="container"><?php

                        /**
                         *  2.1 Load Title
                         *  --------------
                         */
                        printf('<div class="section-title">

                                    <div class="d-sm-flex justify-content-between align-items-center">

                                        <h2 class="mb-xxl-0 mb-xl-0 mb-lg-0 mb-md-0 mb-sm-0 mb-3">%1$s</h2>

                                        <div class="">

                                            <button class="btn btn-sm btn-danger reset_budget me-2" data-alert="%5$s">%4$s</button>

                                            <button class="btn btn-sm btn-default" id="%2$s"><i class="fa fa-plus"></i> %3$s</button>

                                            %6$s

                                        </div>

                                    </div>
                                    
                                </div>',

                                /**
                                 *  1. Title
                                 *  --------
                                 */
                                esc_attr__( 'My Budget', 'sdweddingdirectory-budget-calculator' ),

                                /**
                                 *  2. Button ID
                                 *  ------------
                                 */
                                esc_attr( 'sdweddingdirectory_budget_category' ),

                                /**
                                 *  3. Button Text
                                 *  --------------
                                 */
                                esc_html__( 'Add Budget category', 'sdweddingdirectory-budget-calculator' ),

                                /**
                                 *  4. Translation Ready String
                                 *  ---------------------------
                                 */
                                esc_attr__( 'Reset Budget Data', 'sdweddingdirectory-budget-calculator' ),

                                /**
                                 *  5. Message
                                 *  ----------
                                 */
                                esc_attr__( 'Please confirm you wish to permanently delete budget data', 'sdweddingdirectory-budget-calculator' ),

                                /**
                                 *  6. Security
                                 *  -----------
                                 */
                                wp_nonce_field( 

                                    'sdweddingdirectory_couple_budget_security', 

                                    'sdweddingdirectory_couple_budget_security', 

                                    true, false 
                                )
                        );

                        /** 
                         *  2.2 Load Create New Budget Category Form
                         *  ----------------------------------------
                         */
                        self:: budget_page_content();

                    ?></div><?php
                }
            }
        }

        /** 
         *  2.2 Load Create New Budget Category Form
         *  ----------------------------------------
         */
        public static function budget_page_content(){

            /**
             *  Get Budget Data
             *  ---------------
             */
            $_budget_data   =   parent:: get_budget_data();

            /**
             *  Have Data ?
             *  -----------
             */
            if( parent:: _is_array( $_budget_data ) ){ 

                    /**
                     *  2.2 Load Budget Chart
                     *  --------------------- 
                     */
                    self:: budget_chart();

                    ?>

                    <div class="row">

                        <div class="col-12 col-lg-3">

                            <!-- Tab Start -->
                            <div class="nav flex-column nav-pills theme-tabbing-vertical budget-tab" id="sdweddingdirectory-budget-data-tab" role="tablist"
                            aria-orientation="vertical">

                                <?php

                                    $_tab_counter = absint( '1' );

                                    foreach ( $_budget_data as $key => $value) {
                                      
                                        printf( '<a class="nav-link %1$s" id="%2$s-tab" data-bs-toggle="pill" href="#%2$s" role="tab"
                                          aria-controls="%2$s" aria-selected="true"><i class="%4$s"></i> %3$s</a>', 

                                            /**
                                             *  1. Tab is Active ?
                                             *  ------------------
                                             */
                                             ( $_tab_counter == absint( '1' ) ) 

                                             ?  sanitize_html_class( 'active' )

                                             :  '',

                                             /**
                                              *  2. Tab id
                                              *  ---------
                                              */
                                             sanitize_title( $value[ 'budget_category_name' ] ),

                                             /**
                                              *  3. Tab Name
                                              *  -----------
                                              */
                                             esc_attr( $value[ 'budget_category_name' ] ),

                                            /**
                                             *  4. Category Icon
                                             *  -----------------
                                             */
                                            ( isset( $value[ 'budget_category_icon' ] ) && $value[ 'budget_category_icon' ] !== '' )

                                            ?  esc_attr( $value[ 'budget_category_icon' ] )

                                            :  esc_attr( 'sdweddingdirectory_location_heart' )

                                        );

                                        $_tab_counter++;
                                    }

                                ?>

                            </div>
                            <!-- / Tab Start -->

                        </div>
                        <div class="col-12 col-lg-9 mt-4 mt-md-0">
                          
                            <!-- Tab Content Start -->
                            <div class="tab-content budget-tab-content" id="sdweddingdirectory-budget-data-tabContent">

                              <?php

                                $_tabcontent_counter = absint( '1' );

                                foreach ( $_budget_data as $key => $value) {

                                    /**
                                     *  Extract Args
                                     *  ------------
                                     */
                                    extract( [

                                        'actual_amount'     =>      parent:: get_counter(

                                                                        /**
                                                                         *  1. Which amount to show ?
                                                                         *  -------------------------
                                                                         */
                                                                        esc_attr( 'actual_amount' ),

                                                                        /**
                                                                         *  2. Budget Unique ID
                                                                         *  -------------------
                                                                         */
                                                                        absint( $value[ 'budget_unique_id' ] )
                                                                    ),

                                        'estimate_amount'   =>      parent:: get_counter(

                                                                        /**
                                                                         *  1. Which amount to show ?
                                                                         *  -------------------------
                                                                         */
                                                                        esc_attr( 'estimate_amount' ),

                                                                        /**
                                                                         *  2. Budget Unique ID
                                                                         *  -------------------
                                                                         */
                                                                        absint( $value[ 'budget_unique_id' ] )
                                                                    ),

                                        'paid_amount'       =>      parent:: get_counter(

                                                                        /**
                                                                         *  1. Which amount to show ?
                                                                         *  -------------------------
                                                                         */
                                                                        esc_attr( 'paid_amount' ),

                                                                        /**
                                                                         *  2. Budget Unique ID
                                                                         *  -------------------
                                                                         */
                                                                        absint( $value[ 'budget_unique_id' ] )
                                                                    )
                                    ] );



                                  
                                    printf('<div class="tab-pane fade %1$s" id="%2$s" role="tabpanel" aria-labelledby="%2$s-tab">
                                                <div class="card-shadow">

                                                    <div class="card-shadow-header p-0">
                                                        <div class="d-sm-flex align-items-center justify-content-between">
                                                          <div class="d-flex align-items-center">
                                                              <span class="budget-head-icon"> <i class="%16$s text-white"></i></span> 
                                                              <span class="head-simple">%3$s</span> 
                                                          </div>
                                                          <div class="sdweddingdirectory-budget-table-overview d-flex px-4 align-items-center justify-content-between ">
                                                              <div class="cost-details">
                                                                  <small>%4$s</small>
                                                                  <div class="text-success"><span class="BUDGET_FINAL_COST">%5$s</span></div>
                                                              </div>
                                                              <div class="cost-details">
                                                                  <small>%6$s</small>
                                                                  <div><span class="BUDGET_ESTIMATE_COST">%7$s</span></div>
                                                              </div>
                                                              <div class="remove-btn">

                                                                <a href="javascript:" data-budget-unique-id="%8$s" data-category-name="%3$s" data-category-icon="%16$s" data-category-overview="%9$s" class="edit_budget_category action-links edit"><i class="fa fa-pencil"></i></a> 

                                                                <a href="javascript:" data-alert-removed-budget-category="%10$s" data-budget-unique-id="%8$s" class="removed_budget_category action-links"><i class="fa fa-trash"></i></a>

                                                              </div>

                                                          </div>
                                                        </div>
                                                    </div>

                                                    <div class="card-shadow-body p-0">
                                                        <div class="table-responsive">
                                                            <table class="table table-hover mb-0">

                                                                <thead class="thead-light">%11$s</thead>

                                                                <tbody class="budget_add_data" id="%8$s">%12$s</tbody>

                                                                <tfoot>

                                                                    <tr class="add_new_budget_button">
                                                                        <td colspan="5" class="text-center">
                                                                            <button 
                                                                            data-budget-unique-id="%8$s"
                                                                            class="btn btn-outline-success btn-rounded add_new_budget_row_fields"><i class="fa fa-plus"></i> %13$s</button>
                                                                        </td>
                                                                    </tr>

                                                                    <tr class="budget_counter_overview">
                                                                        <td scope="col" class="BUDGET_TOTAL_AMOUNT_TEXT">%14$s</td>
                                                                        <td scope="col" class="BUDGET_ESTIMATE_COST">%7$s</td>
                                                                        <td scope="col" class="BUDGET_FINAL_COST">%5$s</td>
                                                                        <td scope="col" class="BUDGET_PAID_COST">%15$s</td>
                                                                        <td scope="col" class="">&nbsp;</td>
                                                                    </tr>

                                                                </tfoot>

                                                            </table>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>', 

                                        /**
                                         *  1. Tab is Active ?
                                         *  ------------------
                                         */
                                        ( $_tabcontent_counter == absint( '1' ) )

                                        ?   sprintf( '%1$s %2$s', sanitize_html_class( 'active' ), sanitize_html_class( 'show' ) )

                                        :   '',

                                         /**
                                          *  2. Tab ID
                                          *  ---------
                                          */
                                        sanitize_title( $value[ 'budget_category_name' ] ),

                                         /**
                                          *  3. Tab Name
                                          *  -----------
                                          */
                                        esc_attr( $value[ 'budget_category_name' ] ),

                                        /**
                                         *  4. Translation Ready String.
                                         *  -----------------------------
                                         */
                                        esc_attr__( 'Final Cost', 'sdweddingdirectory-budget-calculator' ),

                                        /**
                                         *  5. Actual Amount 
                                         *  ----------------
                                         */
                                        !   empty( $actual_amount )

                                        ?   sdweddingdirectory_pricing_possition( absint( $actual_amount ) )

                                        :   '',

                                        /**
                                         *  6. Translation Ready String.
                                         *  -----------------------------
                                         */
                                        esc_attr__( 'Estimated cost:', 'sdweddingdirectory-budget-calculator' ),

                                        /**
                                         *  7. Estimate Amount 
                                         *  ------------------
                                         */
                                        !   empty( $estimate_amount )

                                        ?   sdweddingdirectory_pricing_possition( absint(  $estimate_amount  ) )

                                        :   '',

                                         /**
                                          *  8. Budget Data Unique ID
                                          *  ------------------------
                                          */
                                        absint( $value[ 'budget_unique_id' ] ),

                                        /**
                                         *  9. Category Overview
                                         *  ---------------------
                                         */
                                        esc_attr( $value[ 'budget_category_overview' ] ),

                                         /**
                                          *  10. Budget Removed Confirmation : Translation Ready String
                                          *  ---------------------------------------------------------
                                          */
                                        sprintf( esc_attr__( 'Are you sure ? You want to remove %1$s Budget category ?', 'sdweddingdirectory-budget-calculator' ), 

                                            /**
                                             *  1. Budget Name Here
                                             *  -------------------
                                             */
                                            esc_attr( $value[ 'budget_category_name' ] )
                                        ),

                                        /**
                                         *  11. Expense : Translation Strings
                                         *  --------------------------------
                                         */
                                        self:: budget_heading_fields(),

                                        /**
                                         *  12. Budget Category Data
                                         *  -----------------------
                                         */
                                        ( parent:: _is_array( json_decode( $value[ 'budget_json_data' ], true ) ) )

                                        ?   self:: budget_data_table( $value[ 'budget_json_data' ] )

                                        :   self:: budget_data_table(),

                                        /**
                                         *  13. Button Text 
                                         *  --------------
                                         */
                                        esc_attr__( 'Add Budget Item', 'sdweddingdirectory-budget-calculator' ),

                                        /*
                                         *  14. Translation String : Total Amount
                                         *  ------------------------------------
                                         */
                                        esc_attr__( 'Total Amount', 'sdweddingdirectory-budget-calculator' ),

                                        /**
                                         *  15. Paid Amount 
                                         *  --------------
                                         */
                                        !   empty( $paid_amount )

                                        ?   sdweddingdirectory_pricing_possition( absint( $paid_amount ) )

                                        :   '',

                                        /**
                                         *  16. Category Icon
                                         *  -----------------
                                         */
                                        ( isset( $value[ 'budget_category_icon' ] ) && $value[ 'budget_category_icon' ] !== '' )

                                        ?  esc_attr( $value[ 'budget_category_icon' ] )

                                        :  esc_attr( 'sdweddingdirectory_location_heart' )

                                    );

                                    $_tabcontent_counter++;
                                }

                            ?>
                            </div>
                            <!-- / Tab Content Start -->

                        </div>
                    </div>

                <?php
            }

            /**
             *  Current Couple Demo Budget Data Setup Again
             *  -------------------------------------------
             */
            else{

                /**
                 *  Update Default Category Data
                 *  ----------------------------
                 */
                parent:: default_budget_data( [

                    'post_id'       =>      absint( parent:: post_id() )

                ] );

                /**
                 *  ---------------------------------------
                 *  Data Updated : Again call this function
                 *  ---------------------------------------
                 */
                self:: budget_page_content();
            }
        }

        /**
         *  Load Chart
         *  ----------
         */
        public static function budget_chart(){

            ?>
            <div class="card-shadow">
                <div class="card-shadow-body">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <div class="text-center">
                                <div id="sdweddingdirectory_budget_chart"></div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="budget-estimation">
                                <div class="d-flex w-100">
                                    <div class="etimated-cost">
                                        <h3 class="mb-3"><?php esc_attr_e( 'Expenses', 'sdweddingdirectory-budget-calculator' ); ?></h3>
                                        <div class="icon"><i class="sdweddingdirectory_saving_money"></i></div> 
                                        <p class="cost-price"><?php 

                                            echo sdweddingdirectory_pricing_possition( number_format_i18n( absint( parent:: get_data( 'sdweddingdirectory_budget_amount' ) ) ) );

                                        ?></p>
                                        <div><?php esc_attr_e( 'Estimated cost', 'sdweddingdirectory-budget-calculator' ); ?></div>
                                        <div class="mt-3">
                                            <a class="btn btn-link-primary p-0" data-estimate-budget-amount="<?php echo sdweddingdirectory_pricing_possition( absint( parent:: get_data( 'sdweddingdirectory_budget_amount' ) ) ); ?>" id="sdweddingdirectory_budget_amount_button" href="javascript:"><i class="fa fa-pencil"></i> <?php esc_attr_e( 'Edit Budget', 'sdweddingdirectory-budget-calculator' ); ?></a>
                                        </div>
                                    </div>
                                    <div class="etimated-cost border-left">
                                        <h3 class="mb-3"><?php esc_attr_e( 'Budget', 'sdweddingdirectory-budget-calculator' ); ?></h3>
                                        <div class="icon"><i class="sdweddingdirectory_money_stack"></i></div>
                                        <?php

                                            /**
                                             *  Master Budget Summary Final Cost for all budget data counter
                                             *  ------------------------------------------------------------
                                             */
                                            printf( '<p class="cost-price final"><span id="master_actual_amount">%1$s</span></p>',

                                                /**
                                                 *  1. Get Final Cost for all category
                                                 *  ----------------------------------
                                                 */
                                                sdweddingdirectory_pricing_possition( number_format_i18n( absint( parent:: get_budget_counter( 'actual_amount' ) ) ) )
                                            );
                                        ?>
                                        <div><?php esc_attr_e( 'Final cost', 'sdweddingdirectory-budget-calculator' ); ?></div>
                                        <div class="mt-3">
                                        <?php

                                            /**
                                             *  Master Budget Summary Final Cost for all budget data counter
                                             *  ------------------------------------------------------------
                                             */
                                            printf( '%1$s <strong><span id="master_paid_amount">%2$s</span></strong> 
                                                     %3$s <strong><span id="master_pending_amount">%4$s</span></strong>',


                                                /**
                                                 *  1. String Translation
                                                 *  ---------------------
                                                 */
                                                esc_attr__( 'Paid:', 'sdweddingdirectory-budget-calculator' ),

                                                /**
                                                 *  2. Get Final Cost for all category
                                                 *  ----------------------------------
                                                 */
                                                sdweddingdirectory_pricing_possition( absint( parent:: get_budget_counter( 'paid_amount' ) ) ),

                                                /**
                                                 *  3. String Translation
                                                 *  ---------------------
                                                 */
                                                esc_attr__( 'Pending:', 'sdweddingdirectory-budget-calculator' ),

                                                /**
                                                 *  4. Get Final Cost for all category
                                                 *  ----------------------------------
                                                 */
                                                sdweddingdirectory_pricing_possition( absint( 

                                                    parent:: get_budget_counter( 'actual_amount' ) 
                                                    -
                                                    parent:: get_budget_counter( 'paid_amount' ) 

                                                ) )
                                            );
                                        ?>                                                    
                                             
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
        }

        /**
         *  Budger Tab Body Content Table Heading
         *  -------------------------------------
         */
        public static function budget_heading_fields(){

            return 

            sprintf('   <tr>
                            <th scope="col" class="width45">%1$s</th>
                            <th scope="col">%2$s</th>
                            <th scope="col">%3$s</th>
                            <th scope="col">%4$s</th>
                            <th scope="col">%5$s</th>
                        </tr>', 

                        /**
                         *  1. Translation String : Expense
                         *  -------------------------------
                         */
                        esc_attr__( 'Expense', 'sdweddingdirectory-budget-calculator' ),

                        /**
                         *  2. Translation String : Estimate Cost
                         *  -------------------------------------
                         */
                        esc_attr__( 'Estimate Cost', 'sdweddingdirectory-budget-calculator' ),

                        /**
                         *  3. Translation String : Final Cost
                         *  ----------------------------------
                         */
                        esc_attr__( 'Final Cost', 'sdweddingdirectory-budget-calculator' ),

                        /**
                         *  4. Translation String : Paid
                         *  ----------------------------
                         */
                        esc_attr__( 'Paid', 'sdweddingdirectory-budget-calculator' ),

                        /**
                         *  5. Translation String : Action
                         *  -------------------------------
                         */
                        esc_attr__( 'Action', 'sdweddingdirectory-budget-calculator' )
            );
        }

        /**
         *  Budget Tab Content Data
         *  -----------------------
         */
        public static function budget_data_table( $data = '' ){

            if( empty( $data ) ){

                return 

                sprintf( '<tr>
                            <td><input autocomplete="off" type="text"   name="expense"  placeholder="" class="form-control form-control-sm expense_name"/></td>
                            <td><input autocomplete="off" type="number" name="estimate" placeholder="0" class="form-control form-control-sm estimate_amount" /></td>
                            <td><input autocomplete="off" type="number" name="actual"   placeholder="0" class="form-control form-control-sm actual_amount" /></td>
                            <td><input autocomplete="off" type="number" name="paid"     placeholder="0" class="form-control form-control-sm paid_amount" /></td>
                            <td><a href="javascript:" class="action-links budget_field_delete"><i class="fa fa-trash"></i></a></td>
                        </tr>'
                );

            }else{

                $data = json_decode( $data, true );

                $get_formate = '';

                if( parent:: _is_array( $data ) ){

                    foreach ( $data as $key => $value) {
                        
                        $get_formate .=

                        sprintf('<tr>
                                    <td><input autocomplete="off" type="text"   name="expense"  value="%1$s" class="form-control form-control-sm expense_name"/></td>
                                    <td><input autocomplete="off" type="number" name="estimate" placeholder="0" value="%2$s" class="form-control form-control-sm estimate_amount" /></td>
                                    <td><input autocomplete="off" type="number" name="actual"   placeholder="0" value="%3$s" class="form-control form-control-sm actual_amount" /></td>
                                    <td><input autocomplete="off" type="number" name="paid"     placeholder="0" value="%4$s" class="form-control form-control-sm paid_amount" /></td>
                                    <td><a href="javascript:" class="action-links budget_field_delete"><i class="fa fa-trash"></i></a></td>
                                </tr>',

                                /**
                                 *  1. Name of Expence
                                 *  ------------------
                                 */
                                ( isset( $value[ 'expense_name' ] ) && $value[ 'expense_name' ] !== '' )

                                ?   esc_attr( $value[ 'expense_name' ] ) 

                                :   '',

                                /**
                                 *  2. Estimate Amount
                                 *  ------------------
                                 */
                                ( isset( $value[ 'estimate_amount' ] ) && $value[ 'estimate_amount' ] !== '' )

                                ?   absint( $value[ 'estimate_amount' ] ) 

                                :   '',

                                /**
                                 *  3. Actual Amount
                                 *  ----------------
                                 */
                                ( isset( $value[ 'actual_amount' ] ) && $value[ 'actual_amount' ] !== '' )

                                ?   absint( $value[ 'actual_amount' ] ) 

                                :   '',

                                /**
                                 *  4. Paid Amount
                                 *  --------------
                                 */
                                ( isset( $value[ 'paid_amount' ] ) && $value[ 'paid_amount' ] !== '' )

                                ?   absint( $value[ 'paid_amount' ] ) 

                                :   ''
                        );
                    }
                }

                return $get_formate;
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Dashboard_Budget:: get_instance();
}