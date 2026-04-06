<?php
/**
 *  SDWeddingDirectory Couple Budget Calculator AJAX Script Action
 *  ------------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Budget_AJAX' ) && class_exists( 'SDWeddingDirectory_Budget_Database' ) ){

    /**
     *  SDWeddingDirectory Couple Budget Calculator AJAX Script Action
     *  ------------------------------------------------------
     */
    class SDWeddingDirectory_Budget_AJAX extends SDWeddingDirectory_Budget_Database{

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
             *  1. Have AJAX action ?
             *  ---------------------
             */
            if(  wp_doing_ajax()  ){

                /**
                 *  Have AJAX action ?
                 *  ------------------
                 */
                if( isset( $_POST['action'] ) && $_POST['action'] !== '' ){

                    /**
                     *  Have AJAX action ?
                     *  ------------------
                     */
                    $action     =   esc_attr( trim( $_POST['action'] ) );

                    /**
                     *  1.  Bit of security
                     *  -------------------
                     */
                    $allowed_actions = array(

                        /**
                         *  1. Add New Budget Category
                         *  --------------------------
                         */
                        esc_attr( 'sdweddingdirectory_budget_category_add' ),

                        /**
                         *  2. Removed Todo List
                         *  --------------------
                         */
                        esc_attr( 'sdweddingdirectory_removed_budget_category' ),

                        /**
                         *  3. Save Data
                         *  ------------
                         */
                        esc_attr( 'sdweddingdirectory_budget_data_save' ),

                        /**
                         *  4. Edit Budget Category
                         *  -----------------------
                         */
                        esc_attr( 'sdweddingdirectory_budget_category_edit' ),

                        /**
                         *  5. Get Chart Data
                         *  -----------------
                         */
                        esc_attr( 'sdweddingdirectory_estimate_budget_amount' ),

                        /**
                         *  6. Get Chart Data
                         *  -----------------
                         */
                        esc_attr( 'budget_calculator_chart_data' ),

                        /**
                         *   Reset Budget Data
                         *   -----------------
                         */
                        esc_attr( 'sdweddingdirectory_couple_reset_budget' )
                    );

                    /**
                     *  If have action then load this object members
                     *  --------------------------------------------
                     */
                    if( in_array( $action, $allowed_actions) ) {

                        /**
                         *  Check the AJAX action with login user
                         *  -------------------------------------
                         */
                        if( is_user_logged_in() ){

                            /**
                             *  1. If user already login then AJAX action
                             *  -----------------------------------------
                             */
                            add_action( 'wp_ajax_' . $action, [ $this, $action ] );

                        }else{

                            /**
                             *  2. If user not login that mean is front end AJAX action
                             *  -------------------------------------------------------
                             */
                            add_action( 'wp_ajax_nopriv_' . $action, [ $this, $action ] );
                        }
                    }
                }
            }
        }

        /**
         *  1. Add New Todo Task
         *  --------------------
         */
        public static function sdweddingdirectory_budget_category_add(){

            global $post, $wp_query;

            if (  isset( $_POST['security'] ) && wp_verify_nonce( $_POST['security'], esc_attr( 'add_budget_categor_security' ) )  ){

                /**
                 *  Have Database Category ?
                 *  ------------------------
                 */
                $_backend_data =  parent:: get_data( sanitize_key( 'sdweddingdirectory_budget_data' ) );

                /**
                 *  Form Data ( Request Data )
                 *  --------------------------
                 */
                $_form_data =   array( array(

                    'title'                     =>  esc_attr( $_POST[ 'budget_category_name' ] ),

                    'budget_category_name'      =>  esc_attr( $_POST[ 'budget_category_name' ] ),

                    'budget_category_overview'  =>  esc_attr( $_POST[ 'budget_category_overview' ] ),

                    'budget_unique_id'          =>  absint( rand() ),

                    'budget_category_icon'      =>  esc_attr( $_POST[ 'budget_category_icon' ] ),

                    'budget_json_data'          =>  '',

                ) );

                /**
                 *  Check if budget data exists ?
                 *  -----------------------------
                 */
                if( SDWeddingDirectory_Loader:: _is_array( $_backend_data ) ){

                    $_master_budget_category =

                    array_merge( $_backend_data, $_form_data );

                }else{

                    $_master_budget_category =  $_form_data;
                }

                /**
                 *  Update Post Meta
                 *  ----------------
                 */
                update_post_meta(

                    /**
                     *  Get Post ID
                     *  -----------
                     */
                    absint( parent:: post_id() ),

                    /**
                     *  Budget Meta Key
                     *  ---------------
                     */
                    sanitize_key( 'sdweddingdirectory_budget_data' ),

                    /**
                     *  Values
                     *  ------
                     */
                    $_master_budget_category
                );


                /**
                 *  Success Message
                 *  ---------------
                 */
                die( json_encode( array(

                    'message'    => esc_attr__( 'Budget Category Create Successfully!', 'sdweddingdirectory-budget-calculator' ),

                    'notice'     => absint( '1' )

                ) ) );


            }else{

                /**
                 *  Error Message
                 *  -------------
                 */
                die( json_encode( array(

                    'message'    => esc_attr__( 'Security issue found!', 'sdweddingdirectory-budget-calculator' ),

                    'notice'     => absint( '2' )

                ) ) );            

            }
        }

        /**
         *  2. Removed Todo List
         *  --------------------
         */
        public static function sdweddingdirectory_removed_budget_category(){

            if( isset( $_POST[ 'budget_unique_id' ] ) && $_POST[ 'budget_unique_id' ] !== '' ){

                /**
                 *  Have Database Category ?
                 *  ------------------------
                 */
                $_backend_data =  parent:: get_data( sanitize_key( 'sdweddingdirectory_budget_data' ) );

                /** 
                 *  Data exists ?
                 *  -------------
                 */
                if( SDWeddingDirectory_Loader:: _is_array( $_backend_data ) ){

                    foreach ( $_backend_data as $key => $value) {
                        
                        if( absint( $_POST[ 'budget_unique_id' ] ) == absint( $value[ 'budget_unique_id' ] ) ){

                            /**
                             *  Get Category Data Name
                             *  ----------------------
                             */
                            $_category_name = esc_attr( $value[ 'budget_category_name' ] );

                            /**
                             *  Removed Key and Value in Array ( Database )
                             *  -------------------------------------------
                             */
                            unset( $_backend_data[ absint( $key ) ] );

                            /**
                             *  Update New Data
                             *  ---------------
                             */
                            update_post_meta( 

                                /**
                                 *  1. Post ID
                                 *  ----------
                                 */
                                absint( parent:: post_id() ),

                                /**
                                 *  2. Meta key
                                 *  -----------
                                 */
                                sanitize_key( 'sdweddingdirectory_budget_data' ),

                                /**
                                 *  3. Value update in database
                                 *  ---------------------------
                                 */
                                $_backend_data
                            );

                            /**
                             *  Success Message
                             *  ---------------
                             */
                            die( json_encode( array(

                                'message'    => sprintf( esc_attr__( '%1$s Budget Category Removed Successfully!', 'sdweddingdirectory-budget-calculator' ),

                                                    /**
                                                     *  1. Category Name
                                                     *  ----------------
                                                     */
                                                    $_category_name
                                                ),

                                'notice'     => absint( '1' )

                            ) ) );

                        }
                    }

                }else{

                    /**
                     *  Error Message
                     *  -------------
                     */
                    die( json_encode( array(

                        'message'    => esc_attr__( 'Security issue found!', 'sdweddingdirectory-budget-calculator' ),

                        'notice'     => absint( '2' )

                    ) ) );
                }

            }else{

                /**
                 *  Error Message
                 *  -------------
                 */
                die( json_encode( array(

                    'message'    => esc_attr__( 'Security issue found!', 'sdweddingdirectory-budget-calculator' ),

                    'notice'     => absint( '2' )

                ) ) );
            }
        }

        /**
         *  3. Save Data
         *  ------------
         */
        public static function sdweddingdirectory_budget_data_save(){

            $_condition_1 = isset( $_POST[ 'budget_unique_id' ] ) && $_POST[ 'budget_unique_id' ] !== '';

            if( $_condition_1 ){

                /**
                 *  Have Database Category ?
                 *  ------------------------
                 */
                $_backend_data =  parent:: get_data( sanitize_key( 'sdweddingdirectory_budget_data' ) );

                /** 
                 *  Data exists ?
                 *  -------------
                 */
                if( SDWeddingDirectory_Loader:: _is_array( $_backend_data ) ){

                    foreach ( $_backend_data as $key => $value) {
                        
                        if( absint( $_POST[ 'budget_unique_id' ] ) == absint( $value[ 'budget_unique_id' ] ) ){

                            /**
                             *  Get Category Name
                             *  -----------------
                             */
                            $_category_name = esc_attr( $value[ 'budget_category_name' ] );

                            /**
                             *  Update key Budget Data
                             *  ----------------------
                             */
                            $_backend_data[ absint( $key ) ] = array(

                                'title'                     =>  esc_attr( $value[ 'budget_category_name' ] ),

                                'budget_category_name'      =>  esc_attr( $value[ 'budget_category_name' ] ),

                                'budget_category_overview'  =>  esc_attr( $value[ 'budget_category_overview' ] ),

                                'budget_unique_id'          =>  absint( $value[ 'budget_unique_id' ] ),

                                'budget_category_icon'      =>  esc_attr( $value[ 'budget_category_icon' ] ),

                                'budget_json_data'          =>  preg_replace('/\s+/', ' ', trim( $_POST[ 'budget_json_data' ]  ) )
                            );

                            /**
                             *  Update New Data
                             *  ---------------
                             */
                            update_post_meta( 

                                /**
                                 *  1. Post ID
                                 *  ----------
                                 */
                                absint( parent:: post_id() ),

                                /**
                                 *  2. Meta key
                                 *  -----------
                                 */
                                sanitize_key( 'sdweddingdirectory_budget_data' ),

                                /**
                                 *  3. Value update in database
                                 *  ---------------------------
                                 */
                                $_backend_data
                            );

                            /**
                             *  Success Message
                             *  ---------------
                             */
                            die( json_encode( array(

                                'message'    => sprintf( esc_attr__( '%1$s Budget Category Data Save Successfully!', 'sdweddingdirectory-budget-calculator' ),

                                                    /**
                                                     *  1. Category Name
                                                     *  ----------------
                                                     */
                                                    $_category_name
                                                ),

                                'notice'                    =>  absint( '1' ),

                                'budget_category'           =>  parent:: get_number_of_category( 'budget_category_name' ),

                                'estimate_amount'           =>  parent:: get_number_of_category( 'estimate_amount' ),

                                'master_estimate_amount'    =>  sdweddingdirectory_pricing_possition(

                                                                    number_format_i18n( absint( parent:: get_budget_counter( 'estimate_amount' ) ) )
                                                                ),

                                'master_paid_amount'        =>  sdweddingdirectory_pricing_possition( 

                                                                    number_format_i18n( absint( parent:: get_budget_counter( 'paid_amount' ) ) )
                                                                ),

                                'master_actual_amount'      =>  sdweddingdirectory_pricing_possition( 

                                                                    number_format_i18n( absint( parent:: get_budget_counter( 'actual_amount' ) ) )
                                                                ),

                                'master_pending_amount'     =>  sdweddingdirectory_pricing_possition( 

                                                                    number_format_i18n(

                                                                        absint( 

                                                                            // 1
                                                                            absint( parent:: get_budget_counter( 'actual_amount' ) )
                                                                            -
                                                                            // 2
                                                                            absint( parent:: get_budget_counter( 'paid_amount' ) )

                                                                        )
                                                                    )
                                                                ),
                            ) ) );
                        }
                    }

                }else{

                    /**
                     *  Error Message
                     *  -------------
                     */
                    die( json_encode( array(

                        'message'    => esc_attr__( 'Security issue found!', 'sdweddingdirectory-budget-calculator' ),

                        'notice'     => absint( '2' )

                    ) ) );
                }

            }else{

                /**
                 *  Error Message
                 *  -------------
                 */
                die( json_encode( array(

                    'message'    => esc_attr__( 'Security issue found!', 'sdweddingdirectory-budget-calculator' ),

                    'notice'     => absint( '2' )

                ) ) );
            }
        }

        /**
         *  4. Edit New Todo Task
         *  ---------------------
         */
        public static function sdweddingdirectory_budget_category_edit(){

            global $post, $wp_query;

            if (  isset( $_POST['security'] ) && wp_verify_nonce( $_POST['security'], esc_attr( 'edit_budget_categor_security' ) )  ){

                /**
                 *  Have Database Category ?
                 *  ------------------------
                 */
                $_backend_data =  parent:: get_data( sanitize_key( 'sdweddingdirectory_budget_data' ) );

                /** 
                 *  Data exists ?
                 *  -------------
                 */
                if( SDWeddingDirectory_Loader:: _is_array( $_backend_data ) ){

                    foreach ( $_backend_data as $key => $value) {
                        
                        if( absint( $_POST[ 'budget_unique_id' ] ) == absint( $value[ 'budget_unique_id' ] ) ){

                            /**
                             *  Get Category Data Name
                             *  ----------------------
                             */
                            $_backend_data[ absint( $key ) ] = array(

                                'title'                     =>  esc_attr( $_POST[ 'budget_category_name' ] ),

                                'budget_category_name'      =>  esc_attr( $_POST[ 'budget_category_name' ] ),

                                'budget_category_overview'  =>  esc_attr( $_POST[ 'budget_category_overview' ] ),

                                'budget_category_icon'      =>  esc_attr( $_POST[ 'budget_category_icon' ] ),

                                'budget_json_data'          =>  trim( $value[ 'budget_json_data' ] ),

                                'budget_unique_id'          =>  absint( $value[ 'budget_unique_id' ] ),
                            );

                            /**
                             *  Update New Data
                             *  ---------------
                             */
                            update_post_meta( 

                                /**
                                 *  1. Post ID
                                 *  ----------
                                 */
                                absint( parent:: post_id() ),

                                /**
                                 *  2. Meta key
                                 *  -----------
                                 */
                                sanitize_key( 'sdweddingdirectory_budget_data' ),

                                /**
                                 *  3. Value update in database
                                 *  ---------------------------
                                 */
                                $_backend_data
                            );

                            /**
                             *  Success Message
                             *  ---------------
                             */
                            die( json_encode( array(

                                'message'    => sprintf( esc_attr__( '%1$s Budget Category Updated Successfully!', 'sdweddingdirectory-budget-calculator' ),

                                                    /**
                                                     *  1. Category Name
                                                     *  ----------------
                                                     */
                                                    esc_attr( $_POST[ 'budget_category_name' ] )
                                                ),

                                'notice'     => absint( '1' )

                            ) ) );

                        }
                    }

                }else{

                    /**
                     *  Error Message
                     *  -------------
                     */
                    die( json_encode( array(

                        'message'    => esc_attr__( 'Security issue found!', 'sdweddingdirectory-budget-calculator' ),

                        'notice'     => absint( '2' )

                    ) ) );
                }


            }else{

                /**
                 *  Error Message
                 *  -------------
                 */
                die( json_encode( array(

                    'message'    => esc_attr__( 'Security issue found!', 'sdweddingdirectory-budget-calculator' ),

                    'notice'     => absint( '2' )

                ) ) );            

            }
        }

        /**
         *  5. Estimate Cost Update in Database
         *  -----------------------------------
         */
        public static function sdweddingdirectory_estimate_budget_amount(){

            global $post, $wp_query;

            if (  isset( $_POST['security'] ) && wp_verify_nonce( $_POST['security'], esc_attr( 'budget_amount_security' ) )  ){

                $budget_amount = absint( $_POST[ 'sdweddingdirectory_budget_amount' ] );

                if( isset( $budget_amount )  && $budget_amount !== '' && $budget_amount !== absint( '0' ) ){

                    /**
                     *  Update Budget Estimate Amount in Database
                     *  -----------------------------------------
                     */
                    update_post_meta( absint( parent:: post_id() ), sanitize_key( 'sdweddingdirectory_budget_amount' ), absint( $budget_amount ) );

                    /**
                     *  Success Message
                     *  ---------------
                     */
                    die( json_encode( array(

                        'message'    => esc_attr__( 'Your Estimate Amount Update Successfully!', 'sdweddingdirectory-budget-calculator' ),

                        'notice'     => absint( '1' ),

                        'reload'     => true,

                    ) ) );

                }else{

                    /**
                     *  Warning Message
                     *  ---------------
                     */
                    die( json_encode( array(

                        'message'    => esc_attr__( 'Please Update Proper Estimate Amount!', 'sdweddingdirectory-budget-calculator' ),

                        'notice'     => absint( '2' ),

                        'reload'     => false,

                    ) ) );
                }

            }else{

                /**
                 *  Error Message
                 *  -------------
                 */
                die( json_encode( array(

                    'message'    => esc_attr__( 'Security issue found!', 'sdweddingdirectory-budget-calculator' ),

                    'notice'     => absint( '2' ),

                    'reload'     => true,

                ) ) ); 
            }
        }

        /**
         *  Budget Calculator Chart Data
         *  ----------------------------
         */
        public static function budget_calculator_chart_data(){

            die( json_encode( [

                'budget_category'   =>  parent:: get_number_of_category( 'budget_category_name' ),

                'estimate_amount'   =>  parent:: get_number_of_category( 'estimate_amount' ),

            ] ) );
        }

        /**
         *  Reset Budget Calculator Data
         *  ----------------------------
         */
        public static function sdweddingdirectory_couple_reset_budget(){

            /**
             *  Couple Registration through AJAX action With Test Security
             *  ----------------------------------------------------------
             */
            if( wp_verify_nonce( $_POST['security'], 'sdweddingdirectory_couple_budget_security' )  ){

                /**
                 *  Default Todo Update
                 *  -------------------
                 */
                parent:: default_budget_data( [

                    'post_id'       =>      absint( parent:: post_id() )

                ] );

                /**
                 *  Reset Todo
                 *  ----------
                 */
                die( json_encode( [

                    'message'       =>      esc_attr__( 'Your Budget Reset Successfully!', 'sdweddingdirectory-budget-calculator' ),

                    'notice'        =>      absint( '1' )

                ] ) );
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Budget_AJAX:: get_instance();
}