<?php
/**
 *  SDWeddingDirectory Guest List Meta
 *  --------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Guestlist_Meta' ) ){

    /**
     *  SDWeddingDirectory Guest List Meta
     *  --------------------------
     */
    class SDWeddingDirectory_Guestlist_Meta {

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
             *  1. Couple Post Guest List Page Metabox
             *  --------------------------------------
             */
            add_filter('sdweddingdirectory/meta', [ $this, 'sdweddingdirectory_guest_list_meta_box' ], absint('10') );
        }

        /**
         *  1. Couple Post Guest List Page Metabox
         *  --------------------------------------
         */
        public static function sdweddingdirectory_guest_list_meta_box( $args = [] ){

            $new_args   =   array(

                'id'        =>  esc_attr( 'SDWeddingDirectory_Couple_Guest_List' ),

                'title'     =>  esc_attr__( 'Couple Guest List', 'sdweddingdirectory-guest-list' ),

                'pages'     =>  array( 'couple' ),

                'context'   =>  esc_attr( 'normal' ),

                'priority'  =>  esc_attr( 'high' ),

                'fields'    =>  array(


                    /**
                     *  Guest list Data
                     *  ---------------
                     */
                    array(

                        'label'     =>  esc_attr__('Guest List', 'sdweddingdirectory-guest-list'),

                        'id'        =>  esc_attr( 'sdweddingdirectory_gust_list_tab' ),

                        'type'      =>  esc_attr( 'tab' ),
                    ),

                        array(

                            'id'          =>    esc_attr( 'guest_list_data' ),

                            'label'       =>    esc_attr__( 'Guest List Data', 'sdweddingdirectory-guest-list' ),

                            'type'        =>    esc_attr( 'list-item' ),

                            'section'     =>    esc_attr( __FUNCTION__ ),

                            'settings'    =>    array(

                                array(

                                    'id'        =>  sanitize_key( 'first_name' ),

                                    'label'     =>  esc_attr__('First Name', 'sdweddingdirectory-guest-list'),

                                    'type'      =>  esc_attr( 'text' ),
                                ),
                                array(

                                    'id'        =>  sanitize_key( 'last_name' ),

                                    'label'     =>  esc_attr__('Last Name', 'sdweddingdirectory-guest-list'),

                                    'type'      =>  esc_attr( 'text' ),
                                ),
                                array(

                                    'id'        =>  sanitize_key( 'guest_event' ),

                                    'label'     =>  esc_attr__('Guest Event', 'sdweddingdirectory-guest-list'),

                                    'type'      =>  esc_attr( 'textarea-simple' ),
                                ),
                                array(

                                    'id'        =>  sanitize_key( 'guest_age' ),

                                    'label'     =>  esc_attr__('Age', 'sdweddingdirectory-guest-list'),

                                    'type'      =>  esc_attr( 'text' ),
                                ),
                                array(

                                    'id'        =>  sanitize_key( 'guest_group' ),

                                    'label'     =>  esc_attr__('Group', 'sdweddingdirectory-guest-list'),

                                    'type'      =>  esc_attr( 'text' ),
                                ),
                                array(

                                    'id'        =>  sanitize_key( 'guest_need_hotel' ),

                                    'label'     =>  esc_attr__('Need Hotel ?', 'sdweddingdirectory-guest-list'),

                                    'type'      =>  esc_attr( 'text' ),
                                ),
                                array(

                                    'id'        =>  sanitize_key( 'guest_email' ),

                                    'label'     =>  esc_attr__('Guest Email', 'sdweddingdirectory-guest-list'),

                                    'type'      =>  esc_attr( 'text' ),
                                ),
                                array(

                                    'id'        =>  sanitize_key( 'guest_contact' ),

                                    'label'     =>  esc_attr__('Guest Contact', 'sdweddingdirectory-guest-list'),

                                    'type'      =>  esc_attr( 'text' ),
                                ),
                                array(

                                    'id'        =>  sanitize_key( 'guest_address' ),

                                    'label'     =>  esc_attr__('Guest Address', 'sdweddingdirectory-guest-list'),

                                    'type'      =>  esc_attr( 'text' ),
                                ),
                                array(

                                    'id'        =>  sanitize_key( 'guest_city' ),

                                    'label'     =>  esc_attr__('Guest City', 'sdweddingdirectory-guest-list'),

                                    'type'      =>  esc_attr( 'text' ),
                                ),
                                array(

                                    'id'        =>  sanitize_key( 'guest_state' ),

                                    'label'     =>  esc_attr__('Guest State', 'sdweddingdirectory-guest-list'),

                                    'type'      =>  esc_attr( 'text' ),
                                ),
                                array(

                                    'id'        =>  sanitize_key( 'guest_zip_code' ),

                                    'label'     =>  esc_attr__('Guest Zip Code', 'sdweddingdirectory-guest-list'),

                                    'type'      =>  esc_attr( 'text' ),
                                ),
                                array(

                                    'id'        =>  sanitize_key( 'guest_comment' ),

                                    'label'     =>  esc_attr__( 'Comment / Message', 'sdweddingdirectory-guest-list' ),

                                    'type'      =>  esc_attr( 'textarea-simple' ),
                                ),
                                array(

                                    'id'        =>  sanitize_key( 'invitation_status' ),

                                    'label'     =>  esc_attr__('Invitation Status', 'sdweddingdirectory-guest-list'),

                                    'type'      =>  esc_attr( 'text' ),
                                ),
                                array(

                                    'id'        =>  sanitize_key( 'guest_unique_id' ),

                                    'label'     =>  esc_attr__('Guest Unique ID', 'sdweddingdirectory-guest-list'),

                                    'type'      =>  esc_attr( 'text' ),
                                ),
                                array(

                                    'id'        =>  sanitize_key( 'guest_member_id' ),

                                    'label'     =>  esc_attr__('Member Unique ID', 'sdweddingdirectory-guest-list'),

                                    'type'      =>  esc_attr( 'text' ),
                                ),
                                array(

                                    'id'        =>  sanitize_key( 'request_missing_info' ),

                                    'label'     =>  esc_attr__( 'Request Missing Information Link Guest Updated ?', 'sdweddingdirectory-guest-list' ),

                                    'type'      =>  esc_attr( 'select' ),

                                    'std'       =>  absint( '0' ),

                                    'choices'   =>  [

                                        [
                                            'src'       =>      '',

                                            'label'     =>      esc_attr__( 'Yes', 'sdweddingdirectory-guest-list' ),

                                            'value'     =>      absint( '1' )
                                        ],

                                        [
                                            'src'       =>      '',

                                            'label'     =>      esc_attr__( 'No', 'sdweddingdirectory-guest-list' ),

                                            'value'     =>      absint( '0' )
                                        ]
                                    ]
                                ),
                            )
                        ),

                    /**
                     *  Guest list *Event* Data list
                     *  ----------------------------
                     */
                    array(

                        'label'         =>  esc_attr__('Event List', 'sdweddingdirectory-guest-list'),

                        'id'            =>  esc_attr( 'sdweddingdirectory_gust_list_event_tab' ),

                        'type'          =>  esc_attr( 'tab' ),
                    ),
                        array(

                            'id'            =>  esc_attr( 'guest_list_event_group' ),

                            'label'         =>  esc_attr__( 'Guest List Event Group', 'sdweddingdirectory-guest-list' ),

                            'type'          =>  esc_attr( 'list-item' ),

                            'section'       =>  esc_attr( __FUNCTION__ ),

                            'settings'      =>  array(

                                array(

                                    'id'     =>  esc_attr( 'event_list' ),

                                    'label'  =>  esc_attr__( 'Event Name', 'sdweddingdirectory-guest-list' ),

                                    'type'   =>  esc_attr( 'text' ),
                                ),
                                array(

                                    'id'     =>  esc_attr( 'event_icon' ),

                                    'label'  =>  esc_attr__( 'Event Icon', 'sdweddingdirectory-guest-list' ),

                                    'type'   =>  esc_attr( 'text' ),
                                ),
                                array(

                                    'id'     =>  esc_attr( 'event_meal' ),

                                    'label'  =>  esc_attr__( 'Meal Options', 'sdweddingdirectory-guest-list' ),

                                    'type'   =>  esc_attr( 'textarea-simple' ),
                                ),
                                array(

                                    'id'     =>  esc_attr('have_meal'),

                                    'label'  =>  esc_html__('Have Meal ?', 'sdweddingdirectory-guest-list'),

                                    'std'    =>  esc_attr( 'off' ),

                                    'type'   =>  esc_attr( 'on-off' ),
                                ),
                                array(

                                    'id'     =>  esc_attr( 'event_unique_id' ),

                                    'label'  =>  esc_attr__( 'Event Unique ID', 'sdweddingdirectory-guest-list' ),

                                    'type'   =>  esc_attr( 'text' ),

                                    'std'    =>  absint( rand() )
                                ),
                            )
                        ),

                    /**
                     *  Guest list *Menu* Data list
                     *  ---------------------------
                     */
                    array(

                        'label'         =>  esc_attr__('Menu List', 'sdweddingdirectory-guest-list'),

                        'id'            =>  esc_attr( 'sdweddingdirectory_gust_list_menu_tab' ),

                        'type'          =>  esc_attr( 'tab' ),
                    ),
                        array(

                            'id'            =>  esc_attr( 'guest_list_menu_group' ),

                            'label'         =>  esc_attr__( 'Guest List Menu Group', 'sdweddingdirectory-guest-list' ),

                            'type'          =>  esc_attr( 'list-item' ),

                            'section'       =>  esc_attr( __FUNCTION__ ),

                            'settings'      =>  array(

                                array(

                                    'id'     =>  esc_attr( 'menu_list' ),

                                    'label'  =>  esc_attr__( 'Add New Menu', 'sdweddingdirectory-guest-list' ),

                                    'type'   =>  esc_attr( 'text' ),
                                ),
                                array(

                                    'id'     =>  esc_attr( 'menu_unique_id' ),

                                    'label'  =>  esc_attr__( 'Menu Unique ID', 'sdweddingdirectory-guest-list' ),

                                    'type'   =>  esc_attr( 'text' ),

                                    'std'    =>  absint( rand() )
                                ),
                            )
                        ),

                    /**
                     *  Guest list *Group* Data
                     *  -----------------------
                     */
                    array(

                        'label'         =>  esc_attr__('Group List', 'sdweddingdirectory-guest-list'),

                        'id'            =>  esc_attr( 'sdweddingdirectory_gust_list_grop_tab' ),

                        'type'          =>  esc_attr( 'tab' ),
                    ),
                        array(

                            'id'            =>  esc_attr( 'guest_list_group' ),

                            'label'         =>  esc_attr__( 'Guest List Group', 'sdweddingdirectory-guest-list' ),

                            'type'          =>  esc_attr( 'list-item' ),

                            'section'       =>  esc_attr( __FUNCTION__ ),

                            'settings'      =>  array(

                                array(

                                    'id'     =>  esc_attr( 'group_name' ),

                                    'label'  =>  esc_attr__( 'Add New Group', 'sdweddingdirectory-guest-list' ),

                                    'type'   =>  esc_attr( 'text' ),
                                ),
                                array(

                                    'id'     =>  esc_attr( 'group_unique_id' ),

                                    'label'  =>  esc_attr__( 'Group Unique ID', 'sdweddingdirectory-guest-list' ),

                                    'type'   =>  esc_attr( 'text' ),

                                    'std'    =>  absint( rand() )
                                ),
                            )
                        ),
                ),
            );

            /**
             *  Return : Meta Filter
             *  --------------------
             */
            return array_merge( $args, array( $new_args ) );
        }
    }   

    /**
     *  Couple Guest List Meta
     *  ----------------------
     */
    SDWeddingDirectory_Guestlist_Meta::get_instance();
}