<?php
/**
 *  SDWeddingDirectory - Budget Menu Filters
 *  --------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Budget_Menu_Filters' ) && class_exists( 'SDWeddingDirectory_Budget_Filters' ) ){

    /**
     *  SDWeddingDirectory - Budget Menu Filters
     *  --------------------------------
     */
    class SDWeddingDirectory_Budget_Menu_Filters extends SDWeddingDirectory_Budget_Filters{

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
             *  1. Couple Profile Menu
             *  ----------------------
             */
            add_filter( 'sdweddingdirectory/couple/dashboard/menu', [ $this, 'menu' ], absint( '50' ), absint( '1' ) );
        }

        /**
         *  Display Couple Tools [ Logout ] Tab
         *  -----------------------------------
         */
        public static function menu( $args = [] ){

            /**
             *  Budget Calculator Menu
             *  ----------------------
             */
            return 

            array_merge( $args, [

                'budget-calculator'     =>      [

                    'menu_show'         =>      apply_filters(

                                                    'sdweddingdirectory/couple-menu/budget-calculator/menu-show',

                                                    true
                                                ),

                    'menu_class'        =>      apply_filters(

                                                    'sdweddingdirectory/couple-menu/budget-calculator/menu-class', 

                                                    ''
                                                ),

                    'menu_id'           =>      apply_filters(

                                                    'sdweddingdirectory/couple-menu/budget-calculator/menu-id', 

                                                    ''
                                                ),

                    'menu_name'         =>      apply_filters(

                                                    'sdweddingdirectory/couple-menu/budget-calculator/menu-name',

                                                    esc_attr__( 'Budget Calculator', 'sdweddingdirectory-budget-calculator' ) 
                                                ),

                    'menu_icon'         =>      apply_filters(

                                                    'sdweddingdirectory/couple-menu/budget-calculator/menu-icon',

                                                    esc_attr( 'sdweddingdirectory-budget' )
                                                ),

                    'menu_active'       =>      parent:: dashboard_page_set( 'budget-calculator' )

                                                ?   sanitize_html_class( 'active' ) 

                                                :   null,

                    'menu_link'         =>      apply_filters(

                                                    'sdweddingdirectory/couple-menu/page-link', 

                                                    esc_attr( 'budget-calculator' ) 
                                                )
                ]

            ]   );
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Budget_Menu_Filters:: get_instance();
}