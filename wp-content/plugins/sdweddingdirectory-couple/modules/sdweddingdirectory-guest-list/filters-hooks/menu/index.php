<?php
/**
 *  SDWeddingDirectory - Guest List Menu Filter
 *  -----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Guest_List_Menu_Filter' ) && class_exists( 'SDWeddingDirectory_Guest_List_Filters' ) ){

    /**
     *  SDWeddingDirectory - Guest List Menu Filter
     *  -----------------------------------
     */
    class SDWeddingDirectory_Guest_List_Menu_Filter extends SDWeddingDirectory_Guest_List_Filters{

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
             *  Dashboard Menu : Guest List Page
             *  --------------------------------
             */
            add_filter( 'sdweddingdirectory/couple/dashboard/menu', [ $this, 'guest_list_menu' ], absint( '70' ) );

            /**
             *  Dashboard Menu : Guest List RSVP
             *  --------------------------------
             */
            add_filter( 'sdweddingdirectory/couple/dashboard/menu', [ $this, 'guest_list_rsvp_menu' ], absint( '71' ) );

            /**
             *  Dashboard Menu : Guest List Summary
             *  -----------------------------------
             */
            add_filter( 'sdweddingdirectory/couple/dashboard/menu', [ $this, 'guest_list_statistic_menu' ], absint( '72' ) );

            /**
             *  Dashboard Menu : Guest List Invitation
             *  --------------------------------------
             */
            add_filter( 'sdweddingdirectory/couple/dashboard/menu', [ $this, 'guest_list_invitation_menu' ], absint( '73' ) );
        }

        /**
         *  Check Current Page is Dashboard + Is my venue / add venue page / update venue page
         *  ----------------------------------------------------------------------------------------
         */
        public static function is_active(){

            /**
             *  Is Guest List Page ?
             *  --------------------
             */
            return      parent:: dashboard_page_set( 'guest-management' ) ||

                        parent:: dashboard_page_set( 'guest-management-rsvp' ) ||

                        parent:: dashboard_page_set( 'guest-management-invitation' ) ||

                        parent:: dashboard_page_set( 'guest-management-statistic' );
        }

        /**
         *  Dashboard Menu : Guest List Page
         *  --------------------------------
         */
        public static function guest_list_menu( $args = [] ){

            /**
             *  Merge Menu
             *  ----------
             */
            return  array_merge( $args, array(

                        'guest-management'  =>  array(

                            'menu_show'     =>  apply_filters(  'sdweddingdirectory/couple-menu/guest-management/menu-show', true  ),

                            'menu_class'    =>  apply_filters(  'sdweddingdirectory/couple-menu/guest-management/menu-class',  '' ),

                            'menu_id'       =>  apply_filters(  'sdweddingdirectory/couple-menu/guest-management/menu-id',  '' ),

                            'menu_name'     =>  apply_filters(  'sdweddingdirectory/couple-menu/guest-management/menu-name', 

                                                    esc_attr__( 'Guest Management', 'couple-guest-list' )
                                                ),

                            'menu_icon'     =>  apply_filters(  'sdweddingdirectory/couple-menu/guest-management/menu-icon', 

                                                    esc_attr( 'sdweddingdirectory-guestlist' ) 
                                                ),

                            'menu_active'   =>  self:: is_active()  ?   sanitize_html_class( 'active' )  :   null,

                            'menu_link'     =>  apply_filters( 'sdweddingdirectory/couple-menu/page-link', esc_attr( 'guest-management' ) )
                        )

                    ) );
        }

        /**
         *  Dashboard Menu : Guest List RSVP
         *  --------------------------------
         */
        public static function guest_list_rsvp_menu( $args = [] ){

            /**
             *  Merge Menu
             *  ----------
             */
            return  array_merge( $args, array(

                        'guest-management-rsvp'   =>  array(

                            'menu_show'     =>  apply_filters(  'sdweddingdirectory/couple-menu/guest_list_rsvp/menu-show', false  ),

                            'menu_class'    =>  apply_filters(  'sdweddingdirectory/couple-menu/guest_list_rsvp/menu-class',  '' ),

                            'menu_id'       =>  apply_filters(  'sdweddingdirectory/couple-menu/guest_list_rsvp/menu-id',  '' ),

                            'menu_name'     =>  apply_filters(  'sdweddingdirectory/couple-menu/guest_list_rsvp/menu-name', 

                                                    esc_attr__( 'Guest Management', 'couple-guest-list' )
                                                ),

                            'menu_icon'     =>  apply_filters(  'sdweddingdirectory/couple-menu/guest_list_rsvp/menu-icon', 

                                                    esc_attr( 'sdweddingdirectory-guestlist' ) 
                                                ),

                            'menu_active'   =>  self:: is_active()  ?   sanitize_html_class( 'active' )  :   null,

                            'menu_link'     =>  apply_filters( 'sdweddingdirectory/couple-menu/page-link', esc_attr( 'guest-management-rsvp' ) )
                        )

                    ) );
        }

        /**
         *  Dashboard Menu : Guest List Summary
         *  -----------------------------------
         */
        public static function guest_list_statistic_menu( $args = [] ){

            /**
             *  Merge Menu
             *  ----------
             */
            return  array_merge( $args, array(

                        'guest-management-statistic'   =>  array(

                            'menu_show'     =>  apply_filters(  'sdweddingdirectory/couple-menu/guest-management-statistic/menu-show', false  ),

                            'menu_class'    =>  apply_filters(  'sdweddingdirectory/couple-menu/guest-management-statistic/menu-class',  '' ),

                            'menu_id'       =>  apply_filters(  'sdweddingdirectory/couple-menu/guest-management-statistic/menu-id',  '' ),

                            'menu_name'     =>  apply_filters(  'sdweddingdirectory/couple-menu/guest-management-statistic/menu-name', 

                                                    esc_attr__( 'Guest Management', 'couple-guest-list' )
                                                ),

                            'menu_icon'     =>  apply_filters(  'sdweddingdirectory/couple-menu/guest-management-statistic/menu-icon',

                                                    esc_attr( 'sdweddingdirectory-guestlist' )
                                                ),

                            'menu_active'   =>  self:: is_active()  ?   sanitize_html_class( 'active' )  :   null,

                            'menu_link'     =>  apply_filters( 'sdweddingdirectory/couple-menu/page-link', esc_attr( 'guest-management-statistic' ) )
                        )

                    ) );
        }

        /**
         *  Dashboard Menu : Guest List Invitation
         *  --------------------------------------
         */
        public static function guest_list_invitation_menu( $args = [] ){

            /**
             *  Merge Menu
             *  ----------
             */
            return      array_merge( $args, array(

                            'guest-management-invitation'   =>  array(

                                'menu_show'     =>  apply_filters(  'sdweddingdirectory/couple-menu/guest-management-invitation/menu-show', false  ),

                                'menu_class'    =>  apply_filters(  'sdweddingdirectory/couple-menu/guest-management-invitation/menu-class',  '' ),

                                'menu_id'       =>  apply_filters(  'sdweddingdirectory/couple-menu/guest-management-invitation/menu-id',  '' ),

                                'menu_name'     =>  apply_filters(  'sdweddingdirectory/couple-menu/guest-management-invitation/menu-name', 

                                                        esc_attr__( 'Invitation Guest List', 'couple-guest-list' )
                                                    ),

                                'menu_icon'     =>  apply_filters(  'sdweddingdirectory/couple-menu/guest-management-invitation/menu-icon',

                                                        esc_attr( 'sdweddingdirectory-guestlist' )
                                                    ),

                                'menu_active'   =>  self:: is_active()  ?   sanitize_html_class( 'active' )  :   null,

                                'menu_link'     =>  apply_filters( 'sdweddingdirectory/couple-menu/page-link', esc_attr( 'guest-management-invitation' ) )
                            )
                    ) );
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Guest_List_Menu_Filter:: get_instance();
}
