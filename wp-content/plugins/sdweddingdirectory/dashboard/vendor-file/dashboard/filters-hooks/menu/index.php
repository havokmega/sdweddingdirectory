<?php
/**
 *  SDWeddingDirectory - Filter & Hooks
 *  ---------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Dashboard_Menu_Filters' ) && class_exists( 'SDWeddingDirectory_Vendor_Dashboard_Filters' ) ){

    /**
     *  SDWeddingDirectory - Filter & Hooks
     *  ---------------------------
     */
    class SDWeddingDirectory_Vendor_Dashboard_Menu_Filters extends SDWeddingDirectory_Vendor_Dashboard_Filters{

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
             *  Vendor Menu
             *  -----------
             */
            add_filter( 'sdweddingdirectory/vendor/dashboard/menu', [ $this, 'menu' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Menu
         *  ----
         */
        public static function menu( $args = [] ){

            return      array_merge( $args, array(

                            'dashboard'         => array(

                                'menu_show'     =>  apply_filters(  'sdweddingdirectory/vendor-menu/dashboard/menu-show', true  ),

                                'menu_class'    =>  apply_filters(  'sdweddingdirectory/vendor-menu/dashboard/menu-class',  '' ),

                                'menu_id'       =>  apply_filters(  'sdweddingdirectory/vendor-menu/dashboard/menu-id',  '' ),

                                'menu_name'     =>  apply_filters(  'sdweddingdirectory/vendor-menu/dashboard/menu-name', esc_attr__( 'Dashboard', 'sdweddingdirectory' )  ),

                                'menu_icon'     =>  apply_filters(  'sdweddingdirectory/vendor-menu/dashboard/menu-icon', esc_attr( 'sdweddingdirectory-dashboard' ) ),

                                'menu_active'   =>  parent:: dashboard_page_set( 'vendor-dashboard' )   ?   sanitize_html_class( 'active' )  :   null,

                                'menu_link'     =>  apply_filters( 'sdweddingdirectory/vendor-menu/page-link', esc_attr( 'vendor-dashboard' ) )
                            ),

                        ) );
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Vendor_Dashboard_Menu_Filters:: get_instance();
}