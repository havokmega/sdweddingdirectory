<?php
/**
 *  SDWeddingDirectory - Couple Website Menu Filter
 *  ---------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Website_Menu_Filter' ) && class_exists( 'SDWeddingDirectory_Couple_Website_Filters' ) ){

    /**
     *  SDWeddingDirectory - Couple Website Menu Filter
     *  ---------------------------------------
     */
    class SDWeddingDirectory_Couple_Website_Menu_Filter extends SDWeddingDirectory_Couple_Website_Filters{

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
            add_filter( 'sdweddingdirectory/couple/dashboard/menu', [ $this, 'menu' ], absint( '80' ), absint( '1' ) );
        }

        /**
         *  Display Couple Tools [ Logout ] Tab
         *  -----------------------------------
         */
        public static function menu( $args = [] ){

            /**
             *  Website Menu
             *  ------------
             */
            return 

            array_merge( $args, [

                'wedding-website'       =>      [

                    'menu_show'         =>      apply_filters(

                                                    'sdweddingdirectory/couple-menu/wedding-website/menu-show',

                                                    true
                                                ),

                    'menu_class'        =>      apply_filters(

                                                    'sdweddingdirectory/couple-menu/wedding-website/menu-class', 

                                                    ''
                                                ),
                    
                    'menu_id'           =>      apply_filters(

                                                    'sdweddingdirectory/couple-menu/wedding-website/menu-id', 

                                                    ''
                                                ),

                    'menu_name'         =>      apply_filters(

                                                    'sdweddingdirectory/couple-menu/wedding-website/menu-name',

                                                    esc_attr__( 'Wedding Website', 'sdweddingdirectory-couple-website' ) 
                                                ),

                    'menu_icon'         =>      apply_filters(

                                                    'sdweddingdirectory/couple-menu/wedding-website/menu-icon',

                                                    esc_attr( 'sdweddingdirectory-website' )
                                                ),

                    'menu_active'       =>      parent:: dashboard_page_set( 'wedding-website' )

                                                ?   sanitize_html_class( 'active' ) 

                                                :   null,

                    'menu_link'         =>      apply_filters(

                                                    'sdweddingdirectory/couple-menu/page-link', 

                                                    esc_attr( 'wedding-website' )
                                                )
                ]

            ] );
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Couple_Website_Menu_Filter:: get_instance();
}