<?php
/**
 *  SDWeddingDirectory - Real Wedding Menu Filter
 *  -----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Real_Wedding_Menu_Filter' ) && class_exists( 'SDWeddingDirectory_Real_Wedding_Filters' ) ){

    /**
     *  SDWeddingDirectory - Real Wedding Menu Filter
     *  -----------------------------------
     */
    class SDWeddingDirectory_Real_Wedding_Menu_Filter extends SDWeddingDirectory_Real_Wedding_Filters{

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
            add_filter( 'sdweddingdirectory/couple/dashboard/menu', [ $this, 'menu' ], absint( '60' ), absint( '1' ) );
        }

        /**
         *  Display Couple Tools [ Logout ] Tab
         *  -----------------------------------
         */
        public static function menu( $args = [] ){

            /**
             *  Real Wedding Menu
             *  -----------------
             */
            return 

            array_merge( $args, [

                'real-wedding'           =>      [

                    'menu_show'         =>      apply_filters(

                                                    'sdweddingdirectory/couple-menu/real-wedding/menu-show',

                                                    true
                                                ),

                    'menu_class'        =>      apply_filters(

                                                    'sdweddingdirectory/couple-menu/real-wedding/menu-class', 

                                                    ''
                                                ),

                    'menu_id'           =>      apply_filters(

                                                    'sdweddingdirectory/couple-menu/real-wedding/menu-id', 

                                                    ''
                                                ),

                    'menu_name'         =>      apply_filters(

                                                    'sdweddingdirectory/couple-menu/real-wedding/menu-name',

                                                    esc_attr__( 'Real Wedding', 'sdweddingdirectory-real-wedding' ) 
                                                ),

                    'menu_icon'         =>      apply_filters(

                                                    'sdweddingdirectory/couple-menu/real-wedding/menu-icon',

                                                    esc_attr( 'sdweddingdirectory-dove' )
                                                ),

                    'menu_active'       =>      parent:: dashboard_page_set( 'real-wedding' )

                                                ?   sanitize_html_class( 'active' ) 

                                                :   null,

                    'menu_link'         =>      apply_filters(

                                                    'sdweddingdirectory/couple-menu/page-link', 

                                                    esc_attr( 'real-wedding' ) 
                                                )
                ]

            ]   );
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Real_Wedding_Menu_Filter:: get_instance();
}