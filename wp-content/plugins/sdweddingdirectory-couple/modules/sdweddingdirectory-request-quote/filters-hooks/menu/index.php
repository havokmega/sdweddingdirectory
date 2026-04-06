<?php
/**
 *  SDWeddingDirectory - Request Quote Menu Filters
 *  ---------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Request_Quote_Menu_Filters' ) && class_exists( 'SDWeddingDirectory_Request_Quote_Filters' ) ){

    /**
     *  SDWeddingDirectory - Request Quote Menu Filters
     *  ---------------------------------------
     */
    class SDWeddingDirectory_Request_Quote_Menu_Filters extends SDWeddingDirectory_Request_Quote_Filters{

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
            add_filter( 'sdweddingdirectory/vendor/dashboard/menu', [ $this, 'menu' ], absint( '50' ), absint( '1' ) );

            /**
             *  Couple Menu
             *  -----------
             */
            add_filter( 'sdweddingdirectory/couple/dashboard/menu', [ $this, 'couple_menu' ], absint( '75' ), absint( '1' ) );
        }

        /**
         *  Menu
         *  ----
         */
        public static function menu( $args = [] ){

            /**
             *  Request Quote Menu
             *  ------------------
             */
            $new_count = parent:: get_new_request_count();

            return  array_merge( $args, array(

                        'request_quote'     =>  array(

                            'menu_show'     =>  apply_filters(  'sdweddingdirectory/vendor-menu/request-quote/menu-show', true  ),

                            'menu_class'    =>  apply_filters(  'sdweddingdirectory/vendor-menu/request-quote/menu-class',  '' ),

                            'menu_id'       =>  apply_filters(  'sdweddingdirectory/vendor-menu/request-quote/menu-id',  '' ),

                            'menu_name'     =>  apply_filters(  'sdweddingdirectory/vendor-menu/request-quote/menu-name',

                                                    esc_attr__( 'Quote Requests', 'sdweddingdirectory' )
                                                ),

                            'menu_icon'     =>  apply_filters(  'sdweddingdirectory/vendor-menu/request-quote/menu-icon',

                                                    esc_attr( 'sdweddingdirectory-request-quote' )
                                                ),

                            'menu_active'   =>  parent:: dashboard_page_set( 'vendor-request-quote' )  ?  sanitize_html_class( 'active' ) : null,

                            'menu_link'     =>  apply_filters( 'sdweddingdirectory/vendor-menu/page-link', esc_attr( 'vendor-request-quote' ) ),

                            'menu_badge'    =>  absint( $new_count )
                        )

                    )  );
        }

        /**
         *  Couple Menu
         *  -----------
         */
        public static function couple_menu( $args = [] ){

            return  array_merge( $args, array(

                        'couple-request-quote'     =>  array(

                            'menu_show'     =>  apply_filters(  'sdweddingdirectory/couple-menu/request-quote/menu-show', true  ),

                            'menu_class'    =>  apply_filters(  'sdweddingdirectory/couple-menu/request-quote/menu-class',  '' ),

                            'menu_id'       =>  apply_filters(  'sdweddingdirectory/couple-menu/request-quote/menu-id',  '' ),

                            'menu_name'     =>  apply_filters(  'sdweddingdirectory/couple-menu/request-quote/menu-name',

                                                    esc_attr__( 'Quote Requests', 'sdweddingdirectory-request-quote' )
                                                ),

                            'menu_icon'     =>  apply_filters(  'sdweddingdirectory/couple-menu/request-quote/menu-icon',

                                                    esc_attr( 'sdweddingdirectory-request-quote' )
                                                ),

                            'menu_active'   =>  parent:: dashboard_page_set( 'couple-request-quote' )  ?  sanitize_html_class( 'active' ) : null,

                            'menu_link'     =>  apply_filters( 'sdweddingdirectory/couple-menu/page-link', esc_attr( 'couple-request-quote' ) )
                        )

                    )  );
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Request_Quote_Menu_Filters:: get_instance();
}
