<?php
/**
 *  SDWeddingDirectory - Reviews Menu Filters
 *  ---------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Reviews_Menu_Filters' ) && class_exists( 'SDWeddingDirectory_Review_Filters' ) ){

    /**
     *  SDWeddingDirectory - Reviews Menu Filters
     *  ---------------------------------
     */
    class SDWeddingDirectory_Reviews_Menu_Filters extends SDWeddingDirectory_Review_Filters{

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
            add_filter( 'sdweddingdirectory/couple/dashboard/menu', [ $this, 'couple_menu' ], absint( '70' ), absint( '1' ) );

            /**
             *  Vendor Menu
             *  -----------
             */
            add_filter( 'sdweddingdirectory/vendor/dashboard/menu', [ $this, 'vendor_menu' ], absint( '70' ), absint( '1' ) );
        }

        /**
         *  Couple Menu
         *  -----------
         */
        public static function couple_menu( $args = [] ){

            return  array_merge( $args, array(

                        'couple-reviews'     =>  array(

                            'menu_show'     =>  apply_filters(  'sdweddingdirectory/couple-menu/couple-reviews/menu-show', true  ),

                            'menu_class'    =>  apply_filters(  'sdweddingdirectory/couple-menu/couple-reviews/menu-class',  '' ),

                            'menu_id'       =>  apply_filters(  'sdweddingdirectory/couple-menu/couple-reviews/menu-id',  '' ),

                            'menu_name'     =>  apply_filters(  'sdweddingdirectory/couple-menu/couple-reviews/menu-name', 

                                                    esc_attr__( 'My Reviews', 'sdweddingdirectory-reviews' )
                                                ),

                            'menu_icon'     =>  apply_filters(  'sdweddingdirectory/couple-menu/couple-reviews/menu-icon',

                                                    esc_attr( 'sdweddingdirectory-reviews' )
                                                ),

                            'menu_active'   =>  parent:: dashboard_page_set( 'couple-reviews' )  ?  sanitize_html_class( 'active' ) : null,

                            'menu_link'     =>  apply_filters( 'sdweddingdirectory/couple-menu/page-link', esc_attr( 'couple-reviews' ) )
                        )
                )  );
        }

        /**
         *  Vendor Menu
         *  -----------
         */
        public static function vendor_menu( $args = [] ){

            return  array_merge( $args, array(

                        'vendor-reviews'     =>  array(

                            'menu_show'     =>  apply_filters(  'sdweddingdirectory/vendor-menu/vendor-reviews/menu-show', true  ),

                            'menu_class'    =>  apply_filters(  'sdweddingdirectory/vendor-menu/vendor-reviews/menu-class',  '' ),

                            'menu_id'       =>  apply_filters(  'sdweddingdirectory/vendor-menu/vendor-reviews/menu-id',  '' ),

                            'menu_name'     =>  apply_filters(  'sdweddingdirectory/vendor-menu/vendor-reviews/menu-name', 

                                                    esc_attr__( 'My Reviews', 'sdweddingdirectory-reviews' )
                                                ),

                            'menu_icon'     =>  apply_filters(  'sdweddingdirectory/vendor-menu/vendor-reviews/menu-icon', 

                                                    esc_attr( 'sdweddingdirectory-reviews' )
                                                ),

                            'menu_active'   =>  parent:: dashboard_page_set( 'vendor-reviews' )  ?  sanitize_html_class( 'active' ) : null,

                            'menu_link'     =>  apply_filters( 'sdweddingdirectory/vendor-menu/page-link', esc_attr( 'vendor-reviews' ) )
                        ),

                    )  );
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Reviews_Menu_Filters:: get_instance();
}