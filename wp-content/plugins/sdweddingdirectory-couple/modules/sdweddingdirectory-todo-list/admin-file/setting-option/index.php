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
 *  -----------------------------------------
 */

if ( ! class_exists( 'SDWeddingDirectory_FrameWork_Checklist_Setting' ) && class_exists( 'SDWeddingDirectory_FrameWork_Couple_Section' )  ) {

    /**
     *  SDWeddingDirectory - Framework - Section - Couple
     *  ------------------------------------------
     */
    class SDWeddingDirectory_FrameWork_Checklist_Setting extends SDWeddingDirectory_FrameWork_Couple_Section {

        /**
         *  Member Variable
         *  ---------------
         */
        private static $instance;

        /**
         *  Initiator
         *  ---------
         */
        public static function get_instance(){

            if( ! isset( self::$instance ) ){

                self::$instance = new self;
            }

            return  self::$instance;
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            return; // Disabled - migrated to native settings

            /**
             *  2. Setting - Venue General Setting
             *  ------------------------------------
             */
            add_filter(  parent:: section_info(), [ $this, 'checklist_setting' ], absint( '20' ), absint( '2' ) );
        }

        /**
         *   Checklist Settings
         *   ------------------
         */
        public static function checklist_setting( $have_setting = [], $have_section = '' ){

            $add_setting    =   array(

                  array(

                      'id'          =>  esc_attr( 'couple_checklist_setting_tab' ),

                      'label'       =>  esc_attr__( 'Checklist', 'sdweddingdirectory-todo-list' ),

                      'type'        =>  esc_attr( 'tab' ),

                      'section'     =>  esc_attr( $have_section )
                  ),

                  array(

                      'id'          =>  esc_attr( 'default_checklist_data_switch' ),

                      'label'       =>  esc_attr__( 'Default Checklist Data Insert in Register Couple ?', 'sdweddingdirectory-todo-list' ),

                      'std'         =>  esc_attr( 'on' ),

                      'type'        =>  esc_attr( 'on-off' ),

                      'section'     =>  esc_attr( $have_section ),
                  ),

                  array(

                      'id'          =>  sanitize_key( 'admin_create_default_todo_list' ),

                      'label'       =>  esc_attr__( 'Default Checklist Data', 'sdweddingdirectory-todo-list' ),

                      'std'         =>  array(

                          /**
                           *  8 Month Ago
                           *  -----------
                           */
                          array(
                              'title'             =>  esc_attr( 'Plan your engagement party' ),
                              'todo_title'        =>  esc_attr( 'Plan your engagement party' ),
                              'todo_overview'     =>  esc_attr( 'Plan your engagement party' ),
                              'todo_period'       =>  esc_attr( '-8 Month' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Schedule an engagement photo shoot' ),
                              'todo_title'        =>  esc_attr( 'Schedule an engagement photo shoot' ),
                              'todo_overview'     =>  esc_attr( 'Schedule an engagement photo shoot' ),
                              'todo_period'       =>  esc_attr( '-8 Month' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Find and book your wedding planner' ),
                              'todo_title'        =>  esc_attr( 'Find and book your wedding planner' ),
                              'todo_overview'     =>  esc_attr( 'Find and book your wedding planner' ),
                              'todo_period'       =>  esc_attr( '-8 Month' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Find and book your venue' ),
                              'todo_title'        =>  esc_attr( 'Find and book your venue' ),
                              'todo_overview'     =>  esc_attr( 'Find and book your venue' ),
                              'todo_period'       =>  esc_attr( '-8 Month' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Create your wedding website' ),
                              'todo_title'        =>  esc_attr( 'Create your wedding website' ),
                              'todo_overview'     =>  esc_attr( 'Create your wedding website' ),
                              'todo_period'       =>  esc_attr( '-8 Month' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Find and book your photographer' ),
                              'todo_title'        =>  esc_attr( 'Find and book your photographer' ),
                              'todo_overview'     =>  esc_attr( 'Find and book your photographer' ),
                              'todo_period'       =>  esc_attr( '-8 Month' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Find and book your florist' ),
                              'todo_title'        =>  esc_attr( 'Find and book your florist' ),
                              'todo_overview'     =>  esc_attr( 'Find and book your florist' ),
                              'todo_period'       =>  esc_attr( '-8 Month' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Find and book your videographer' ),
                              'todo_title'        =>  esc_attr( 'Find and book your videographer' ),
                              'todo_overview'     =>  esc_attr( 'Find and book your videographer' ),
                              'todo_period'       =>  esc_attr( '-8 Month' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Find and order your wedding dress, suit, or tux' ),
                              'todo_title'        =>  esc_attr( 'Find and order your wedding dress, suit, or tux' ),
                              'todo_overview'     =>  esc_attr( 'Find and order your wedding dress, suit, or tux' ),
                              'todo_period'       =>  esc_attr( '-8 Month' ),
                          ),

                          /**
                           *  6 Month Ago
                           *  -----------
                           */
                          array(
                              'title'             =>  esc_attr( 'Find and book your band' ),
                              'todo_title'        =>  esc_attr( 'Find and book your band' ),
                              'todo_overview'     =>  esc_attr( 'Find and book your band' ),
                              'todo_period'       =>  esc_attr( '-6 Month' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Find and book your DJ' ),
                              'todo_title'        =>  esc_attr( 'Find and book your DJ' ),
                              'todo_overview'     =>  esc_attr( 'Find and book your DJ' ),
                              'todo_period'       =>  esc_attr( '-6 Month' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Research songs' ),
                              'todo_title'        =>  esc_attr( 'Research songs' ),
                              'todo_overview'     =>  esc_attr( 'Research songs' ),
                              'todo_period'       =>  esc_attr( '-6 Month' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Update your vendor team' ),
                              'todo_title'        =>  esc_attr( 'Update your vendor team' ),
                              'todo_overview'     =>  esc_attr( 'Update your vendor team' ),
                              'todo_period'       =>  esc_attr( '-6 Month' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Finalize your guest list' ),
                              'todo_title'        =>  esc_attr( 'Finalize your guest list' ),
                              'todo_overview'     =>  esc_attr( 'Finalize your guest list' ),
                              'todo_period'       =>  esc_attr( '-6 Month' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Find and book your officiant' ),
                              'todo_title'        =>  esc_attr( 'Find and book your officiant' ),
                              'todo_overview'     =>  esc_attr( 'Find and book your officiant' ),
                              'todo_period'       =>  esc_attr( '-6 Month' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Find and book your ceremony musician' ),
                              'todo_title'        =>  esc_attr( 'Find and book your ceremony musician' ),
                              'todo_overview'     =>  esc_attr( 'Find and book your ceremony musician' ),
                              'todo_period'       =>  esc_attr( '-6 Month' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Order attire for your wedding party' ),
                              'todo_title'        =>  esc_attr( 'Order attire for your wedding party' ),
                              'todo_overview'     =>  esc_attr( 'Order attire for your wedding party' ),
                              'todo_period'       =>  esc_attr( '-6 Month' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Find and order your wedding cake' ),
                              'todo_title'        =>  esc_attr( 'Find and order your wedding cake' ),
                              'todo_overview'     =>  esc_attr( 'Find and order your wedding cake' ),
                              'todo_period'       =>  esc_attr( '-6 Month' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Find and order your wedding invitations' ),
                              'todo_title'        =>  esc_attr( 'Find and order your wedding invitations' ),
                              'todo_overview'     =>  esc_attr( 'Find and order your wedding invitations' ),
                              'todo_period'       =>  esc_attr( '-6 Month' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Find and order your event rentals' ),
                              'todo_title'        =>  esc_attr( 'Find and order your event rentals' ),
                              'todo_overview'     =>  esc_attr( 'Find and order your event rentals' ),
                              'todo_period'       =>  esc_attr( '-6 Month' ),
                          ),

                          /**
                           *  4 Month Ago
                           *  -----------
                           */
                          array(
                              'title'             =>  esc_attr( 'Find and book your hair and makeup vendor' ),
                              'todo_title'        =>  esc_attr( 'Find and book your hair and makeup vendor' ),
                              'todo_overview'     =>  esc_attr( 'Find and book your hair and makeup vendor' ),
                              'todo_period'       =>  esc_attr( '-4 Month' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Plan and book your honeymoon' ),
                              'todo_title'        =>  esc_attr( 'Plan and book your honeymoon' ),
                              'todo_overview'     =>  esc_attr( 'Plan and book your honeymoon' ),
                              'todo_period'       =>  esc_attr( '-4 Month' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Find and order your partner\'s attire' ),
                              'todo_title'        =>  esc_attr( 'Find and order your partner\'s attire' ),
                              'todo_overview'     =>  esc_attr( 'Find and order your partner\'s attire' ),
                              'todo_period'       =>  esc_attr( '-4 Month' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Purchase your wedding accessories' ),
                              'todo_title'        =>  esc_attr( 'Purchase your wedding accessories' ),
                              'todo_overview'     =>  esc_attr( 'Purchase your wedding accessories' ),
                              'todo_period'       =>  esc_attr( '-4 Month' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Schedule your ceremony rehearsal' ),
                              'todo_title'        =>  esc_attr( 'Schedule your ceremony rehearsal' ),
                              'todo_overview'     =>  esc_attr( 'Schedule your ceremony rehearsal' ),
                              'todo_period'       =>  esc_attr( '-4 Month' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Book accommodations for your wedding night' ),
                              'todo_title'        =>  esc_attr( 'Book accommodations for your wedding night' ),
                              'todo_overview'     =>  esc_attr( 'Book accommodations for your wedding night' ),
                              'todo_period'       =>  esc_attr( '-4 Month' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Find and book your transportation vendor' ),
                              'todo_title'        =>  esc_attr( 'Find and book your transportation vendor' ),
                              'todo_overview'     =>  esc_attr( 'Find and book your transportation vendor' ),
                              'todo_period'       =>  esc_attr( '-4 Month' ),
                          ),

                          /**
                           *  2 Month Ago
                           *  -----------
                           */
                          array(
                              'title'             =>  esc_attr( 'Purchase your wedding rings' ),
                              'todo_title'        =>  esc_attr( 'Purchase your wedding rings' ),
                              'todo_overview'     =>  esc_attr( 'Purchase your wedding rings' ),
                              'todo_period'       =>  esc_attr( '-2 Month' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Purchase guest book' ),
                              'todo_title'        =>  esc_attr( 'Purchase guest book' ),
                              'todo_overview'     =>  esc_attr( 'Purchase guest book' ),
                              'todo_period'       =>  esc_attr( '-2 Month' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Order alcohol for your wedding' ),
                              'todo_title'        =>  esc_attr( 'Order alcohol for your wedding' ),
                              'todo_overview'     =>  esc_attr( 'Order alcohol for your wedding' ),
                              'todo_period'       =>  esc_attr( '-2 Month' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Write your vows' ),
                              'todo_title'        =>  esc_attr( 'Write your vows' ),
                              'todo_overview'     =>  esc_attr( 'Write your vows' ),
                              'todo_period'       =>  esc_attr( '-2 Month' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Plan your wedding ceremony' ),
                              'todo_title'        =>  esc_attr( 'Plan your wedding ceremony' ),
                              'todo_overview'     =>  esc_attr( 'Plan your wedding ceremony' ),
                              'todo_period'       =>  esc_attr( '-2 Month' ),
                          ),
                          
                          /**
                           *  1 Month Ago
                           *  -----------
                           */
                          array(
                              'title'             =>  esc_attr( 'Finalize your vendor team' ),
                              'todo_title'        =>  esc_attr( 'Finalize your vendor team' ),
                              'todo_overview'     =>  esc_attr( 'Finalize your vendor team' ),
                              'todo_period'       =>  esc_attr( '-1 Month' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Call guests who have not RSVP\'d' ),
                              'todo_title'        =>  esc_attr( 'Call guests who have not RSVP\'d' ),
                              'todo_overview'     =>  esc_attr( 'Call guests who have not RSVP\'d' ),
                              'todo_period'       =>  esc_attr( '-1 Month' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Write a sweet note to your partner' ),
                              'todo_title'        =>  esc_attr( 'Write a sweet note to your partner' ),
                              'todo_overview'     =>  esc_attr( 'Write a sweet note to your partner' ),
                              'todo_period'       =>  esc_attr( '-1 Month' ),
                          ),


                          /**
                           *  2 Weeks Before
                           *  --------------
                           */
                          array(
                              'title'             =>  esc_attr( 'Confirm final details with all of your vendors' ),
                              'todo_title'        =>  esc_attr( 'Confirm final details with all of your vendors' ),
                              'todo_overview'     =>  esc_attr( 'Confirm final details with all of your vendors' ),
                              'todo_period'       =>  esc_attr( '-2 Weeks' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Assign duties to wedding party' ),
                              'todo_title'        =>  esc_attr( 'Assign duties to wedding party' ),
                              'todo_overview'     =>  esc_attr( 'Assign duties to wedding party' ),
                              'todo_period'       =>  esc_attr( '-2 Weeks' ),
                          ),

                          /**
                           *  1 Weeks Ago
                           *  -----------
                           */
                          array(
                              'title'             =>  esc_attr( 'Prepare toasts' ),
                              'todo_title'        =>  esc_attr( 'Prepare toasts' ),
                              'todo_overview'     =>  esc_attr( 'Prepare toasts' ),
                              'todo_period'       =>  esc_attr( '-1 Weeks' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Confirm all final deposits' ),
                              'todo_title'        =>  esc_attr( 'Confirm all final deposits' ),
                              'todo_overview'     =>  esc_attr( 'Confirm all final deposits' ),
                              'todo_period'       =>  esc_attr( '-1 Weeks' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Pack for your honeymoon' ),
                              'todo_title'        =>  esc_attr( 'Pack for your honeymoon' ),
                              'todo_overview'     =>  esc_attr( 'Pack for your honeymoon' ),
                              'todo_period'       =>  esc_attr( '-1 Weeks' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Pull together last-minute essentials' ),
                              'todo_title'        =>  esc_attr( 'Pull together last-minute essentials' ),
                              'todo_overview'     =>  esc_attr( 'Pull together last-minute essentials' ),
                              'todo_period'       =>  esc_attr( '-1 Weeks' ),
                          ),

                          /**
                           *  1 Days Ago
                           *  ----------
                           */
                          array(
                              'title'             =>  esc_attr( 'Drop off decor to reception venue' ),
                              'todo_title'        =>  esc_attr( 'Drop off decor to reception venue' ),
                              'todo_overview'     =>  esc_attr( 'Drop off decor to reception venue' ),
                              'todo_period'       =>  esc_attr( '-1 Days' ),
                          ),

                          /**
                           *   Congratulations! You're married!
                           *   --------------------------------
                           */
                          array(
                              'title'             =>  esc_attr( 'Congratulations! You\'re married!' ),
                              'todo_title'        =>  esc_attr( 'Congratulations! You\'re married!' ),
                              'todo_overview'     =>  esc_attr( 'Congratulations! You\'re married!' ),
                              'todo_period'       =>  esc_attr( '0 Days' ),
                          ),

                          /**
                           *  After Days
                           *  ----------
                           */
                          array(
                              'title'             =>  esc_attr( 'Review your vendor team' ),
                              'todo_title'        =>  esc_attr( 'Review your vendor team' ),
                              'todo_overview'     =>  esc_attr( 'Review your vendor team' ),
                              'todo_period'       =>  esc_attr( '+1 Days' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Store your wedding attire' ),
                              'todo_title'        =>  esc_attr( 'Store your wedding attire' ),
                              'todo_overview'     =>  esc_attr( 'Store your wedding attire' ),
                              'todo_period'       =>  esc_attr( '+1 Days' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Send thank-you notes' ),
                              'todo_title'        =>  esc_attr( 'Send thank-you notes' ),
                              'todo_overview'     =>  esc_attr( 'Send thank-you notes' ),
                              'todo_period'       =>  esc_attr( '+1 Days' ),
                          ),
                          array(
                              'title'             =>  esc_attr( 'Change your last name' ),
                              'todo_title'        =>  esc_attr( 'Change your last name' ),
                              'todo_overview'     =>  esc_attr( 'Change your last name' ),
                              'todo_period'       =>  esc_attr( '+1 Days' ),
                          ),
                          array(
                              'title'             =>  sprintf( 'Submit your Real Wedding to be featured on %1$s', 

                                                          /**
                                                           *  1. Blog Info
                                                           *  ------------
                                                           */
                                                          esc_attr( get_bloginfo( 'title' ) ) 
                                                      ),

                              'todo_title'        =>  sprintf( 'Submit your Real Wedding to be featured on %1$s', 

                                                          /**
                                                           *  1. Blog Info
                                                           *  ------------
                                                           */
                                                          esc_attr( get_bloginfo( 'title' ) )
                                                      ),

                              'todo_overview'     =>  sprintf( 'Submit your Real Wedding to be featured on %1$s', 

                                                          /**
                                                           *  1. Blog Info
                                                           *  ------------
                                                           */
                                                          esc_attr( get_bloginfo( 'title' ) )
                                                      ),

                              'todo_period'       =>  esc_attr( '+4 Days' ),
                          ),
                      ),

                      'type'      =>  esc_attr( 'list-item' ),

                      'section'   =>  esc_attr( $have_section ),

                      'condition' =>  esc_attr( 'default_checklist_data_switch:is(on)' ),

                      'settings'  =>  array(

                          array(

                              'id'      =>  sanitize_key( 'todo_title' ),

                              'label'   =>  esc_attr__( 'Todo Title', 'sdweddingdirectory-todo-list' ),

                              'type'    =>  esc_attr( 'text' ),
                          ),

                          array(

                              'id'      =>  sanitize_key( 'todo_overview' ),

                              'label'   =>  esc_attr__( 'Todo Overview', 'sdweddingdirectory-todo-list' ),

                              'type'    =>  esc_attr( 'text' ),
                          ),

                          array(

                              'id'      =>  esc_attr( 'todo_period' ),

                              'label'   =>  esc_attr__('Todo Time Period Added When Couple Register', 'sdweddingdirectory-todo-list'),

                              'type'    =>  esc_attr( 'select' ),

                              'choices' =>  function_exists( 'sdweddingdirectory_checklist_time_period_theme_option' )
                                    
                                            ?   array_merge(

                                                    sdweddingdirectory_checklist_time_period_theme_option( 'Month', '0', '12' ),

                                                    sdweddingdirectory_checklist_time_period_theme_option( 'Weeks', '0', '12' ),

                                                    sdweddingdirectory_checklist_time_period_theme_option( 'Days', '0', '12' )
                                                )
                                  
                                            :   [],
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
    SDWeddingDirectory_FrameWork_Checklist_Setting::get_instance();
}