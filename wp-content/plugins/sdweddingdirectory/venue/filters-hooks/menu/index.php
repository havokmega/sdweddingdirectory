<?php
/**
 *  SDWeddingDirectory - Venue Menu Filters
 *  ---------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Menu_Filters' ) && class_exists( 'SDWeddingDirectory_Vendor_Venue_Filters' ) ){

    /**
     *  SDWeddingDirectory - Venue Menu Filters
     *  ---------------------------------
     */
    class SDWeddingDirectory_Venue_Menu_Filters extends SDWeddingDirectory_Vendor_Venue_Filters{

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
             *  Dashboard Menu : My Venue
             *  ---------------------------
             */
            add_filter( 'sdweddingdirectory/vendor/dashboard/menu', [ $this, 'my_venue_menu' ], absint( '40' ) );

            /**
             *  Dashboard Menu : Add New Venue
             *  --------------------------------
             */
            add_filter( 'sdweddingdirectory/vendor/dashboard/menu', [ $this, 'add_new_venue_menu' ], absint( '41' ) );

            /**
             *  Dashboard Menu : Update Venue
             *  -------------------------------
             */
            add_filter( 'sdweddingdirectory/vendor/dashboard/menu', [ $this, 'update_venue_menu' ], absint( '42' ) );
        }

        /**
         *  Current Vendor Account Type
         *  ---------------------------
         */
        public static function account_type(){

            $user_id = absint( get_current_user_id() );

            $account_type = $user_id > 0

                            ? sanitize_key( get_user_meta( $user_id, sanitize_key( 'sdwd_vendor_account_type' ), true ) )

                            : '';

            if( ! in_array( $account_type, [ 'vendor', 'venue' ], true ) ){

                $account_type = esc_attr( 'vendor' );
            }

            return $account_type;
        }

        /**
         *  Is Venue Account?
         *  -----------------
         */
        public static function user_can_manage_venues(){

            if( self:: account_type() === esc_attr( 'venue' ) ){
                return true;
            }

            /**
             *  Keep page reachable when user is already in venue area.
             */
            return self:: is_my_venue_page();
        }

        /**
         *  Check Current Page is Dashboard + Is my venue / add venue page / update venue page
         *  ----------------------------------------------------------------------------------------
         */
        public static function is_my_venue_page(){

            $_condition_1   =   parent:: dashboard_page_set( 'vendor-venue' );

            $_condition_2   =   parent:: dashboard_page_set( 'add-venue' );

            $_condition_3   =   parent:: dashboard_page_set( 'update-venue' );

            return  $_condition_1 || $_condition_2 || $_condition_3;
        }

        /**
         *  Dashboard Menu : My Venue
         *  ---------------------------
         */
        public static function my_venue_menu( $args = [] ){

                /**
                 *  Merge Menu
                 *  ----------
                 */
                return  array_merge( $args, array(

                            'venues'   =>  array(

                                'menu_show'     =>  apply_filters(  'sdweddingdirectory/vendor-menu/my-venue/menu-show', false  ),

                                'menu_class'    =>  apply_filters(  'sdweddingdirectory/vendor-menu/my-venue/menu-class',  '' ),

                                'menu_id'       =>  apply_filters(  'sdweddingdirectory/vendor-menu/my-venue/menu-id',  '' ),

                                'menu_name'     =>  apply_filters(  'sdweddingdirectory/vendor-menu/my-venue/menu-name', 

                                                        esc_attr__( 'My Venues', 'sdweddingdirectory-venue' )
                                                    ),

                                'menu_icon'     =>  apply_filters(  'sdweddingdirectory/vendor-menu/my-venue/menu-icon', 

                                                        esc_attr( 'sdweddingdirectory-my-venue' ) 
                                                    ),

                                'menu_active'   =>  self:: is_my_venue_page()     ?   sanitize_html_class( 'active' )  :   null,

                                'menu_link'     =>  apply_filters( 'sdweddingdirectory/vendor-menu/page-link', esc_attr( 'vendor-venue' ) )
                            )

                        ) );
        }

        /**
         *  Dashboard Menu : Add New Venue
         *  --------------------------------
         */
        public static function add_new_venue_menu( $args = [] ){

                /**
                 *  Merge Menu
                 *  ----------
                 */
                return  array_merge( $args, array(

                            'add_venue'   =>  array(

                                'menu_show'     =>  apply_filters(  'sdweddingdirectory/vendor-menu/add-venue/menu-show', false  ),

                                'menu_class'    =>  apply_filters(  'sdweddingdirectory/vendor-menu/add-venue/menu-class',  '' ),

                                'menu_id'       =>  apply_filters(  'sdweddingdirectory/vendor-menu/add-venue/menu-id',  '' ),

                                'menu_name'     =>  apply_filters(  'sdweddingdirectory/vendor-menu/add-venue/menu-name', 

                                                        esc_attr__( 'Add Venue', 'sdweddingdirectory-venue' )
                                                    ),

                                'menu_icon'     =>  apply_filters(  'sdweddingdirectory/vendor-menu/add-venue/menu-icon', 

                                                        esc_attr( 'sdweddingdirectory-my-venue' ) 
                                                    ),

                                'menu_active'   =>  self:: is_my_venue_page()     ?   sanitize_html_class( 'active' )  :   null,

                                'menu_link'     =>  apply_filters( 'sdweddingdirectory/vendor-menu/page-link', esc_attr( 'add-venue' ) )
                            )

                        ) );
        }

        /**
         *  Dashboard Menu : Update Venue
         *  -------------------------------
         */
        public static function update_venue_menu( $args = [] ){

                /**
                 *  Merge Menu
                 *  ----------
                 */
                return  array_merge( $args, array(

                            'update_venue'   =>  array(

                                'menu_show'     =>  apply_filters(  'sdweddingdirectory/vendor-menu/update-venue/menu-show', false  ),

                                'menu_class'    =>  apply_filters(  'sdweddingdirectory/vendor-menu/update-venue/menu-class',  '' ),

                                'menu_id'       =>  apply_filters(  'sdweddingdirectory/vendor-menu/update-venue/menu-id',  '' ),

                                'menu_name'     =>  apply_filters(  'sdweddingdirectory/vendor-menu/update-venue/menu-name', 

                                                        esc_attr__( 'Update Venue', 'sdweddingdirectory-venue' )
                                                    ),

                                'menu_icon'     =>  apply_filters(  'sdweddingdirectory/vendor-menu/update-venue/menu-icon', 

                                                        esc_attr( 'sdweddingdirectory-my-venue' ) 
                                                    ),

                                'menu_active'   =>  self:: is_my_venue_page()     ?   sanitize_html_class( 'active' )  :   null,

                                'menu_link'     =>  apply_filters( 'sdweddingdirectory/vendor-menu/page-link', esc_attr( 'update-venue' ) )
                            )

                        ) );
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Venue_Menu_Filters:: get_instance();
}
