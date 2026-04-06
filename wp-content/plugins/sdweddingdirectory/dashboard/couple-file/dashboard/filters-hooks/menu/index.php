<?php
/**
 *  SDWeddingDirectory - Couple Dashboard Filter & Hooks
 *  --------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Dashboard_Menu_Filters' ) && class_exists( 'SDWeddingDirectory_Couple_Dashboard_Filters' ) ){

    /**
     *  SDWeddingDirectory - Couple Dashboard Filter & Hooks
     *  --------------------------------------------
     */
    class SDWeddingDirectory_Couple_Dashboard_Menu_Filters extends SDWeddingDirectory_Couple_Dashboard_Filters{

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
            add_filter( 'sdweddingdirectory/couple/dashboard/menu', [ $this, 'menu' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Display Couple Tools [ Logout ] Tab
         *  -----------------------------------
         */
        public static function menu( $args = [] ){

                /**
                 *  ----------------
                 *  Couple Dashboard
                 *  ----------------
                 */
                return      array_merge( $args, [

                                'couple-dashboard'      =>      [

                                    'menu_show'         =>      apply_filters(

                                                                    'sdweddingdirectory/couple-menu/dashboard/menu-show', 

                                                                    true
                                                                ),

                                    'menu_class'        =>      apply_filters(

                                                                    'sdweddingdirectory/couple-menu/dashboard/menu-class', 

                                                                    ''
                                                                ),

                                    'menu_id'           =>      apply_filters(

                                                                    'sdweddingdirectory/couple-menu/dashboard/menu-id', 

                                                                    ''
                                                                ),

                                    'menu_name'         =>      apply_filters(

                                                                    'sdweddingdirectory/couple-menu/dashboard/menu-name', 

                                                                    esc_attr__( 'Dashboard', 'sdweddingdirectory' ) 
                                                                ),

                                    'menu_icon'         =>      apply_filters(

                                                                    'sdweddingdirectory/couple-menu/dashboard/menu-icon',

                                                                    esc_attr( 'sdweddingdirectory-heart-ring' )
                                                                ),

                                    'menu_active'       =>      parent:: dashboard_page_set( 'couple-dashboard' ) 

                                                                ?   sanitize_html_class( 'active' ) 

                                                                :   null,

                                    'menu_link'         =>      apply_filters( 

                                                                    'sdweddingdirectory/couple-menu/page-link', 

                                                                    esc_attr( 'couple-dashboard' ) 
                                                                )
                            ]   ]  );
        }
    }

   /**
    *  Couple Dashboard Filer Object Call
    *  ----------------------------------
    */
    SDWeddingDirectory_Couple_Dashboard_Menu_Filters:: get_instance();
}