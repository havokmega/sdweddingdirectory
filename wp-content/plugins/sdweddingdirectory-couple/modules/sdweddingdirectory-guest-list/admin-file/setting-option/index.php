<?php
/**
 *  -------------------------------------
 *  OptionTree ( Theme Option Framework )
 *  -------------------------------------
 *  @author : By - Derek Herman
 *  ---------------------------
 *  @link - https://wordpress.org/plugins/option-tree/
 *  --------------------------------------------------
 *  Fields : https://github.com/valendesigns/option-tree-theme/blob/master/inc/theme-options.php
 *  --------------------------------------------------------------------------------------------
 *  SDWeddingDirectory - Framework - Section - Couple
 *  ------------------------------------------
 */
if ( ! class_exists( 'SDWeddingDirectory_FrameWork_Guest_List_Setting' ) && class_exists( 'SDWeddingDirectory_FrameWork_Couple_Section' )  ) {

    /**
     *  SDWeddingDirectory - Framework - Section - Couple
     *  -----------------------------------------
     */
    class SDWeddingDirectory_FrameWork_Guest_List_Setting extends SDWeddingDirectory_FrameWork_Couple_Section {

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

            return; // Disabled - migrated to native settings

            /**
             *  1. Setting - Venue General Setting
             *  ------------------------------------
             */
            add_filter(  parent:: section_info(), [ $this, 'guest_list_setting' ], absint( '30' ), absint( '2' )  );
        }

        /**
         *   Guest List Setting Tab
         *   ----------------------
         */
        public static function guest_list_setting( $have_setting = [], $have_section = '' ){

            $add_setting    =   array(

                    array(

                        'id'          =>  esc_attr( 'sdweddingdirectory_guest_list_setting_tab' ),

                        'label'       =>  esc_attr__( 'Guest List Setting', 'sdweddingdirectory-guest-list' ),

                        'type'        =>  esc_attr( 'tab' ),

                        'section'     =>  esc_attr( $have_section ),
                    ),

                    array(

                      'id'          =>  esc_attr( 'guest_list_group' ),

                      'label'       =>  esc_attr__( 'Group List', 'sdweddingdirectory-guest-list' ),

                      'std'         =>  array(

                                              array(

                                                  'title'       =>  esc_attr__( 'Groom Sister', 'sdweddingdirectory-guest-list' ),

                                                  'group_name'  =>  esc_attr__( 'Groom Sister', 'sdweddingdirectory-guest-list' ) 
                                              ),

                                              array(

                                                  'title'       =>  esc_attr__( 'Bride Friend', 'sdweddingdirectory-guest-list' ),

                                                  'group_name'  =>  esc_attr__( 'Bride Friend', 'sdweddingdirectory-guest-list' ) 
                                              ),

                                              array(

                                                  'title'       =>  esc_attr__( 'Groom Friend', 'sdweddingdirectory-guest-list' ),

                                                  'group_name'  =>  esc_attr__( 'Groom Friend', 'sdweddingdirectory-guest-list' ) 
                                              ),

                                              array(

                                                  'title'       =>  esc_attr__( 'Bride Family', 'sdweddingdirectory-guest-list' ),

                                                  'group_name'  =>  esc_attr__( 'Bride Family', 'sdweddingdirectory-guest-list' ) 
                                              ),

                                              array(

                                                  'title'       =>  esc_attr__( 'Groom Family', 'sdweddingdirectory-guest-list' ),

                                                  'group_name'  =>  esc_attr__( 'Groom Family', 'sdweddingdirectory-guest-list' ) 
                                              ),

                                          ),

                      'type'          =>  esc_attr( 'list-item' ),

                      'section'       =>  esc_attr( $have_section ),

                      'settings'      =>  array(

                                              array(

                                                  'id'          =>  esc_attr( 'group_name' ),

                                                  'label'       =>  esc_attr__( 'Add New Group', 'sdweddingdirectory-guest-list' ),

                                                  'type'        =>  esc_attr( 'text' ),
                                              ),
                                          )
                    ),

                    array(

                      'id'          =>  esc_attr( 'guest_list_menu_group' ),

                      'label'       =>  esc_attr__( 'Menu List', 'sdweddingdirectory-guest-list' ),

                      'std'         =>  array(

                                              array(

                                                  'title'       =>  esc_attr__( 'Beef', 'sdweddingdirectory-guest-list' ),

                                                  'menu_list'   =>  esc_attr__( 'Beef', 'sdweddingdirectory-guest-list' )
                                              ),

                                              array(

                                                  'title'       =>  esc_attr__( 'Chicken', 'sdweddingdirectory-guest-list' ),

                                                  'menu_list'   =>  esc_attr__( 'Chicken', 'sdweddingdirectory-guest-list' )
                                              ),

                                              array(

                                                  'title'       =>  esc_attr__( 'Child Meal', 'sdweddingdirectory-guest-list' ),

                                                  'menu_list'   =>  esc_attr__( 'Child Meal', 'sdweddingdirectory-guest-list' )
                                              ),

                                              array(

                                                  'title'       =>  esc_attr__( 'Fish', 'sdweddingdirectory-guest-list' ),

                                                  'menu_list'   =>  esc_attr__( 'Fish', 'sdweddingdirectory-guest-list' )
                                              ),

                                              array(

                                                  'title'       =>  esc_attr__( 'Lamb', 'sdweddingdirectory-guest-list' ),

                                                  'menu_list'   =>  esc_attr__( 'Lamb', 'sdweddingdirectory-guest-list' )
                                              ),

                                              array(

                                                  'title'       =>  esc_attr__( 'Vegetarian', 'sdweddingdirectory-guest-list' ),

                                                  'menu_list'   =>  esc_attr__( 'Vegetarian', 'sdweddingdirectory-guest-list' )
                                              ),

                                              array(

                                                  'title'       =>  esc_attr__( 'Other', 'sdweddingdirectory-guest-list' ),

                                                  'menu_list'   =>  esc_attr__( 'Other', 'sdweddingdirectory-guest-list' )
                                              ),
                                        ),

                      'type'        =>  esc_attr( 'list-item' ),

                      'section'     =>  esc_attr( $have_section ),

                      'settings'    =>  array(

                                            array(

                                                'id'          =>  esc_attr( 'menu_list' ),

                                                'label'       =>  esc_attr__( 'Add New Menu', 'sdweddingdirectory-guest-list' ),

                                                'type'        =>  esc_attr( 'text' ),
                                            ),
                                        )
                    ),

                    array(

                      'id'          =>  esc_attr( 'guest_list_event_group' ),

                      'label'       =>  esc_attr__( 'Event List', 'sdweddingdirectory-guest-list' ),

                      'std'         =>  array(

                                            array(

                                                'title'         =>  esc_attr__( 'Wedding', 'sdweddingdirectory-guest-list' ),

                                                'event_list'    =>  esc_attr__( 'Wedding', 'sdweddingdirectory-guest-list' ),

                                                'have_meal'     =>  esc_attr( 'on' ),

                                                'event_icon'    =>  esc_attr( 'flaticon-046-wedding' ),
                                            ),

                                            array(

                                                'title'         =>  esc_attr__( 'Rehearsal Dinner', 'sdweddingdirectory-guest-list' ),

                                                'event_list'    =>  esc_attr__( 'Rehearsal Dinner', 'sdweddingdirectory-guest-list' ),

                                                'have_meal'     =>  esc_attr( 'on' ),

                                                'event_icon'    =>  esc_attr( 'flaticon-016-gift' ),
                                            ),

                                            array(

                                                'title'         =>  esc_attr__( 'Shower', 'sdweddingdirectory-guest-list' ),

                                                'event_list'    =>  esc_attr__( 'Shower', 'sdweddingdirectory-guest-list' ),

                                                'have_meal'     =>  esc_attr( 'on' ),

                                                'event_icon'    =>  esc_attr( 'sdweddingdirectory-ballon-heart' ),
                                            ),

                                            array(

                                                'title'         =>  esc_attr__( 'Dance Party', 'sdweddingdirectory-guest-list' ),

                                                'event_list'    =>  esc_attr__( 'Dance Party', 'sdweddingdirectory-guest-list' ),

                                                'have_meal'     =>  esc_attr( 'on' ),

                                                'event_icon'    =>  esc_attr( 'sdweddingdirectory-wine' ),
                                            ),
                                        ),

                      'type'        =>  esc_attr( 'list-item' ),

                      'section'     =>  esc_attr( $have_section ),

                      'settings'    =>  array(

                                            array(

                                                'id'          =>  esc_attr( 'event_list' ),

                                                'label'       =>  esc_attr__( 'Add New Event', 'sdweddingdirectory-guest-list' ),

                                                'type'        =>  esc_attr( 'text' ),
                                            ),

                                            array(

                                                'id'          =>  esc_attr( 'event_icon' ),

                                                'label'       =>  esc_attr__( 'Event Icon', 'sdweddingdirectory-guest-list' ),

                                                'type'        =>  esc_attr( 'text' ),

                                                // 'type'        =>  esc_attr( 'select' ),

                                                // 'choices'     =>  apply_filters( 'sdweddingdirectory_icons_set_for_ot', [] )
                                            ),

                                            array(

                                                'id'          =>  esc_attr( 'have_meal' ),

                                                'label'       =>  esc_attr__( 'Have Meal ?', 'sdweddingdirectory-guest-list' ),

                                                'std'         =>  esc_attr( 'off' ),

                                                'type'        =>  esc_attr( 'on-off' ),
                                            ),
                                        ),
                    ),
            );

            return      array_merge( $have_setting, $add_setting );
        }
    }

    /**
     *  SDWeddingDirectory - Framework - Section - Couple
     *  ------------------------------------------
     */
    SDWeddingDirectory_FrameWork_Guest_List_Setting::get_instance();
}