<?php
/**
 *  Option Tree Plugin
 *  ------------------
 *
 *  @link( https://github.com/valendesigns/option-tree )
 *
 *  @link( https://github.com/valendesigns/option-tree-theme/blob/master/inc/meta-boxes.php, link)
 */

if( ! class_exists( 'SDWeddingDirectory_Budget_Calculator_Meta' ) && class_exists( 'SDWeddingDirectory_Budget_Plugin_Admin_Files' ) ){

    /**
     *  SDWeddingDirectory Budget Category Page Meta
     *  ------------------------------------
     */
    class SDWeddingDirectory_Budget_Calculator_Meta extends SDWeddingDirectory_Budget_Plugin_Admin_Files {

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
             *  1. Budget Calculator Tools Meta Filter
             *  --------------------------------------
             */
            add_filter( 'sdweddingdirectory/meta', [ $this, 'sdweddingdirectory_couple_meta' ], absint('10') );
        }

        /**
         *  1. Budget Calculator Tools Meta Filter
         *  --------------------------------------
         */
        public static function sdweddingdirectory_couple_meta( $args = [] ){

            $new_args   =   array(

                'id'        =>  esc_attr( 'sdweddingdirectory_couple_budget_list' ),

                'title'     =>  esc_attr__( 'Couple Budget Tool', 'sdweddingdirectory-budget-calculator' ),

                'pages'     =>  array('couple'),

                'context'   =>  esc_attr( 'normal' ),

                'priority'  =>  esc_attr( 'high' ),

                'fields'    =>  array(


                    /**
                     *  1. Tab : Budget Amount
                     *  ----------------------
                     */
                    array(

                        'label'     =>  esc_attr__( 'Budget Amount', 'sdweddingdirectory-budget-calculator' ),

                        'id'        =>  esc_attr( 'sdweddingdirectory_sdweddingdirectory_budget_amount_tab' ),

                        'type'      =>  esc_attr( 'tab' ),
                    ),

                        array(

                            'id'    =>  sanitize_key( 'sdweddingdirectory_budget_amount' ),

                            'label' =>  esc_attr__( 'Budget Amount', 'sdweddingdirectory-budget-calculator' ),

                            'type'  =>  esc_attr( 'text' ),
                        ),

                    /**
                     *  2. Tab : Budget List
                     *  --------------------
                     */
                    array(

                        'label'     =>  esc_attr__( 'Budget List', 'sdweddingdirectory-budget-calculator' ),

                        'id'        =>  esc_attr( 'sdweddingdirectory_budget_list_tab' ),

                        'type'      =>  esc_attr( 'tab' ),
                    ),

                    array(

                        'id'        =>  sanitize_key( 'sdweddingdirectory_budget_data' ),

                        'type'      =>  esc_attr( 'list-item' ),

                        'operator'  =>  esc_attr( 'or' ),

                        'choices'   =>  [],

                        'settings'  =>  array(


                            array(

                                'id'        =>  sanitize_key( 'budget_category_name' ),

                                'label'     =>  esc_attr__( 'Budget Category Name', 'sdweddingdirectory-budget-calculator' ),

                                'type'      =>  esc_attr( 'text' ),
                            ),

                            array(

                                'id'        =>  sanitize_key( 'budget_category_overview' ),

                                'label'     =>  esc_attr__( 'Budget Category Overview', 'sdweddingdirectory-budget-calculator' ),

                                'type'      =>  esc_attr( 'text' ),
                            ),

                            array(

                                'id'        =>  sanitize_key( 'budget_category_icon' ),

                                'label'     =>  esc_attr__( 'Budget Category Icon', 'sdweddingdirectory-budget-calculator' ),

                                'type'      =>  esc_attr( 'select' ),

                                'choices'   =>  apply_filters( 'sdweddingdirectory_icons_set_for_ot', [] ),
                            ),

                            array(

                                'id'        =>  sanitize_key( 'budget_json_data' ),

                                'label'     =>  esc_attr__( 'Budget JSON Data', 'sdweddingdirectory-budget-calculator' ),

                                'type'      =>  esc_attr( 'textarea-simple' ),
                            ),

                            array(

                                'id'        =>  sanitize_key( 'budget_unique_id' ),

                                'label'     =>  esc_attr__( 'Budget Unique ID', 'sdweddingdirectory-budget-calculator' ),

                                'type'      =>  esc_attr( 'text' ),
                            ),
                        ),
                    ),
                ),
            );

            /**
             *  Return : Meta
             *  -------------
             */
            return  array_merge( $args, array( $new_args ) );
        }
    }

    /**
     *  Budget Calculator : Meta Object
     *  -------------------------------
     */
    SDWeddingDirectory_Budget_Calculator_Meta::get_instance();
}