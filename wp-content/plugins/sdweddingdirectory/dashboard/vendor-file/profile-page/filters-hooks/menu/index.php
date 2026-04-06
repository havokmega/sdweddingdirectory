<?php
/**
 *  SDWeddingDirectory - Vendor Filter & Hooks
 *  ----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Profile_Menu_Filters' ) && class_exists( 'SDWeddingDirectory_Vendor_Profile_Filters' ) ){

    /**
     *  SDWeddingDirectory - Vendor Filter & Hooks
     *  ----------------------------------
     */
    class SDWeddingDirectory_Vendor_Profile_Menu_Filters extends SDWeddingDirectory_Vendor_Profile_Filters{

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
             *  1. My Profile Menu
             *  ------------------
             */
            add_filter( 'sdweddingdirectory/vendor/dashboard/menu', [ $this, 'menu' ], absint( '20' ), absint( '1' ) );
        }

        /**
         *  Menu
         *  ----
         */
        public static function menu( $args = [] ){

            return      array_merge( $args, array(

                            'profile'           =>  array(

                                'menu_show'     =>  apply_filters(  'sdweddingdirectory/vendor-menu/my-profile/menu-show', true  ),

                                'menu_class'    =>  apply_filters(  'sdweddingdirectory/vendor-menu/my-profile/menu-class',  '' ),

                                'menu_id'       =>  apply_filters(  'sdweddingdirectory/vendor-menu/my-profile/menu-id',  '' ),

                                'menu_name'     =>  apply_filters(  'sdweddingdirectory/vendor-menu/my-profile/menu-name', esc_attr__( 'My Profile', 'sdweddingdirectory' )  ),

                                'menu_icon'     =>  apply_filters(  'sdweddingdirectory/vendor-menu/my-profile/menu-icon', esc_attr( 'sdweddingdirectory-my-profile' ) ),

                                'menu_active'   =>  parent:: dashboard_page_set( 'vendor-profile' )   ?   sanitize_html_class( 'active' )  :   null,

                                'menu_link'     =>  apply_filters( 'sdweddingdirectory/vendor-menu/page-link', esc_attr( 'vendor-profile' ) )
                            )

                        ) );
        }
    }

    /**
     *  SDWeddingDirectory - Vendor Filter & Hooks
     *  ----------------------------------
     */
    SDWeddingDirectory_Vendor_Profile_Menu_Filters:: get_instance();
}
