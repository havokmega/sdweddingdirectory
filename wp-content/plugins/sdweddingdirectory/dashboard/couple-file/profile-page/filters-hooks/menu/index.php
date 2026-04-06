<?php
/**
 *  Filter & Hooks
 *  --------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Profile_Menu_Filters' ) && class_exists( 'SDWeddingDirectory_Couple_Profile_Filters' ) ){

    /**
     *  Filter & Hooks
     *  --------------
     */
    class SDWeddingDirectory_Couple_Profile_Menu_Filters extends SDWeddingDirectory_Couple_Profile_Filters{

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
            add_filter( 'sdweddingdirectory/couple/dashboard/menu', [ $this, 'menu' ], absint( '20' ), absint( '1' ) );
        }

        /**
         *  Display Couple Tools [ Logout ] Tab
         *  -----------------------------------
         */
        public static function menu( $args = [] ){

            /**
             *  Dashboard Menu Filter Added
             *  ---------------------------
             */
            return  array_merge( $args, [

                        /**
                         *  Menu Slug
                         *  ---------
                         */
                        'my-profile'        =>      [

                            'menu_show'     =>      apply_filters(

                                                        'sdweddingdirectory/couple-menu/my-profile/menu-show',

                                                        true
                                                    ),

                            'menu_class'     =>      apply_filters(

                                                        'sdweddingdirectory/couple-menu/my-profile/menu-class',

                                                        ''
                                                    ),

                            'menu_id'       =>      apply_filters(

                                                        'sdweddingdirectory/couple-menu/my-profile/menu-id', 

                                                        ''
                                                    ),

                            'menu_name'     =>      apply_filters(

                                                        'sdweddingdirectory/couple-menu/my-profile/menu-name', 

                                                        esc_attr__( 'My Profile', 'sdweddingdirectory' ) 
                                                    ),

                            'menu_icon'     =>      apply_filters(

                                                        'sdweddingdirectory/couple-menu/my-profile/menu-icon',

                                                        esc_attr( 'sdweddingdirectory-my-profile' )
                                                    ),

                            'menu_active'   =>      parent:: dashboard_page_set( 'my-profile' ) 

                                                    ?   sanitize_html_class( 'active' ) 

                                                    :   null,

                            'menu_link'     =>      apply_filters(

                                                        'sdweddingdirectory/couple-menu/page-link', 

                                                        esc_attr( 'my-profile' ) 
                                                    )
                    ]   ]   );
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Couple_Profile_Menu_Filters:: get_instance();
}