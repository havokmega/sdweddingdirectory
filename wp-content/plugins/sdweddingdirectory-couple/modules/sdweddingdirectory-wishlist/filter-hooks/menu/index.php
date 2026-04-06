<?php
/**
 *  SDWeddingDirectory - WishList Menu Filters
 *  ----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_WishList_Menu_Filters' ) && class_exists( 'SDWeddingDirectory_WishList_Filters' ) ){

    /**
     *  SDWeddingDirectory - WishList Menu Filters
     *  ----------------------------------
     */
    class SDWeddingDirectory_WishList_Menu_Filters extends SDWeddingDirectory_WishList_Filters{

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
             *  Couple Menu
             *  -----------
             */
            add_filter( 'sdweddingdirectory/couple/dashboard/menu', [ $this, 'menu' ],  absint( '30' ), absint( '1' )  );
        }

        /**
         *  Couple Menu
         *  -----------
         */
        public static function menu( $args = [] ){

            /**
             *  Merge Menu
             *  ----------
             */
            return      array_merge( $args, array(

                            'vendor-manager'          =>  array(

                                'menu_show'     =>  apply_filters(  'sdweddingdirectory/couple-menu/vendor-manager/menu-show', true  ),

                                'menu_class'    =>  apply_filters(  'sdweddingdirectory/couple-menu/vendor-manager/menu-class',  '' ),

                                'menu_id'       =>  apply_filters(  'sdweddingdirectory/couple-menu/vendor-manager/menu-id',  '' ),

                                'menu_name'     =>  apply_filters(  'sdweddingdirectory/couple-menu/vendor-manager/menu-name', 

                                                        esc_attr__( 'Vendor Manager', 'sdweddingdirectory-wishlist' )
                                                    ),

                                'menu_icon'     =>  apply_filters(  'sdweddingdirectory/couple-menu/vendor-manager/menu-icon', 

                                                        esc_attr( 'sdweddingdirectory-vendor-manager' )
                                                    ),

                                'menu_active'   =>  parent:: dashboard_page_set( 'vendor-manager' )  ?   sanitize_html_class( 'active' )  :   null,

                                'menu_link'     =>  apply_filters( 'sdweddingdirectory/couple-menu/page-link', esc_attr( 'vendor-manager' ) )
                            )

                        ) );
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_WishList_Menu_Filters:: get_instance();
}