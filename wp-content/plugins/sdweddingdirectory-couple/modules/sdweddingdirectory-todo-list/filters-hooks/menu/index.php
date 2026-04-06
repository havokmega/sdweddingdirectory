<?php
/**
 *  SDWeddingDirectory - Menu Filters
 *  -------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_ToDo_Menu_Filters' ) && class_exists( 'SDWeddingDirectory_Todo_Filters' ) ){

    /**
     *  SDWeddingDirectory - Menu Filters
     *  -------------------------
     */
    class SDWeddingDirectory_ToDo_Menu_Filters extends SDWeddingDirectory_Todo_Filters{

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
             *  1. Couple Menu
             *  --------------
             */
            add_filter( 'sdweddingdirectory/couple/dashboard/menu', [ $this, 'menu' ], absint( '40' ), absint( '1' ) );
        }

        /**
         *  Display Couple Tools [ Logout ] Tab
         *  -----------------------------------
         */
        public static function menu( $args = [] ){

            /**
             *  Todo Menu
             *  ---------
             */
            return 

            array_merge( $args, [

                'checklist'             =>      [

                    'menu_show'         =>      apply_filters(

                                                    'sdweddingdirectory/couple-menu/checklist/menu-show',

                                                    true
                                                ),

                    'menu_class'        =>      apply_filters(

                                                    'sdweddingdirectory/couple-menu/checklist/menu-class', 

                                                    ''
                                                ),

                    'menu_id'           =>      apply_filters(

                                                    'sdweddingdirectory/couple-menu/checklist/menu-id', 

                                                    ''
                                                ),

                    'menu_name'         =>      apply_filters(

                                                    'sdweddingdirectory/couple-menu/checklist/menu-name',

                                                    esc_attr__( 'Checklist', 'sdweddingdirectory-todo-list' )
                                                ),

                    'menu_icon'         =>      apply_filters(

                                                    'sdweddingdirectory/couple-menu/checklist/menu-icon',

                                                    esc_attr( 'sdweddingdirectory-checklist' )
                                                ),

                    'menu_active'       =>      parent:: dashboard_page_set( 'checklist' )

                                                ?   sanitize_html_class( 'active' ) 

                                                :   null,

                    'menu_link'         =>      apply_filters(

                                                    'sdweddingdirectory/couple-menu/page-link', 

                                                    esc_attr( 'checklist' ) 
                                                )
                ]

            ]   );
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_ToDo_Menu_Filters:: get_instance();
}