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
 *  SDWeddingDirectory - Framework - Section - Venue Email
 *  ------------------------------------------------
 */
if ( ! class_exists( 'SDWeddingDirectory_FrameWork_Venue_Email_Section' ) ) {

    /**
     *  SDWeddingDirectory - Framework - Section - Venue Email
     *  ------------------------------------------------
     */
    class SDWeddingDirectory_FrameWork_Venue_Email_Section extends SDWeddingDirectory_FrameWork{

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
         *  1. Section Name
         *  ---------------
         */
        public static function section_name(){

            /**
             *  Section Name
             *  ------------
             */
            return  esc_attr__( 'Venue Email', 'sdweddingdirectory-venue' );
        }

        /**
         *  2. Section ID
         *  -------------
         */
        public static function section_id(){

            /**
             *  Section ID
             *  ----------
             */
            return  sanitize_title( 'sdweddingdirectory_venue_email_section' );
        }

        /**
         *  3. Section Info
         *  ---------------
         */
        public static function section_info(){

            /**
             *  Tab Section
             *  -----------
             */
            return      sprintf( 'sdweddingdirectory_framework_{%1$s}_settings',  esc_attr( self:: section_id() ) );
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  1. Section - Couple
             *  -------------------
             */
            add_filter( 'sdweddingdirectory_framework', function( $args = [] ) {

                /**
                 *  Add SDWeddingDirectory - Framework Section
                 *  ----------------------------------
                 */
                return      array_merge_recursive(

                                /**
                                 *  1. Have Args ?
                                 *  --------------
                                 */
                                $args, 

                                /**
                                 *  2. Add New Args 
                                 *  ---------------
                                 */
                                parent:: sdweddingdirectory_register_framework_section( array(

                                    'section_name'   =>   esc_attr( self:: section_name() ),

                                    'section_id'     =>   sanitize_title( self:: section_id() )

                                ) )
                            );

            }, absint( '120' ) );

            /**
             *  2. Setting - Venue Expired [ 1 Week Reminder ]
             *  ------------------------------------------------
             */
            add_filter( self:: section_info(), [ $this, 'vendor_venue_expire_week' ], absint( '10' ), absint( '2' ) );

            /**
             *  3. Setting - Venue Expired [ 1 Day Reminder ]
             *  -----------------------------------------------
             */
            add_filter( self:: section_info(), [ $this, 'vendor_venue_expire_tomorrow' ], absint( '20' ), absint( '2' ) );

            /**
             *  4. Setting - Venue Expired
             *  ----------------------------
             */
            add_filter( self:: section_info(), [ $this, 'vendor_venue_expire_today' ], absint( '30' ), absint( '2' ) );
        }

        /**
         *   Setting : New Register Vendor
         *   -----------------------------
         */
        public static function vendor_venue_expire_week( $have_setting = [], $have_section = '' ){

            $email_title    =   esc_attr( 'venue-expired-week' );

            $add_setting    =   array(

                array(

                    'id'          => esc_attr( 'email-tab-' . $email_title ),

                    'label'     =>  esc_attr__( 'Venue Expired in Week', 'sdweddingdirectory-venue' ),

                    'type'      =>  esc_attr( 'tab' ),

                    'section'   =>  esc_attr( $have_section ),
                ),

                array(

                    'id'          =>  esc_attr( 'email-subject-' . $email_title ),

                    'label'     =>  esc_attr__( 'Email Subject', 'sdweddingdirectory-venue' ),

                    'std'       =>  'Your Venue will Expired in Week!',

                    'type'      =>  esc_attr( 'text' ),

                    'section'   =>  esc_attr( $have_section ),
                ),

                array(

                  'id'          =>  esc_attr( 'email-body-' . $email_title ),

                  'label'       =>  esc_attr__( 'Email Body Content', 'sdweddingdirectory-venue' ),

                  'type'        =>  esc_attr( 'textarea-simple' ),

                  'std'         =>  '<p>Hello {{vendor_username}},</p><p>We are inform you that, as your pricing package {{pricing_plan_name}} expired on {{pricing_plan_expire}}. please select your favorite pricing plan.</p><p><a href="{{vendor_login_redirect_link_pricing}}" style="{{primary_button_style}}">Vendor Pricing Package</a></p><p>Thank you.</p>',

                  'section'     =>  esc_attr( $have_section ),

                  'rows'        =>  absint( '4' ),
                ),
            );

            /**
             *  Merge Admin Email Setting
             *  -------------------------
             */
            return      array_merge( $have_setting, array_merge(

                            $add_setting,   parent:: sdweddingdirectory_setting_option_admin_emails( $email_title, $have_section ),

                        ) );
        }

        /**
         *   Setting : New Register Vendor
         *   -----------------------------
         */
        public static function vendor_venue_expire_tomorrow( $have_setting = [], $have_section = '' ){

            $email_title    =   esc_attr( 'venue-expired-tomorrow' );

            $add_setting    =   array(

                array(

                    'id'          => esc_attr( 'email-tab-' . $email_title ),

                    'label'     =>  esc_attr__( 'Venue Expired Tomorrow', 'sdweddingdirectory-venue' ),

                    'type'      =>  esc_attr( 'tab' ),

                    'section'   =>  esc_attr( $have_section ),
                ),

                array(

                    'id'          =>  esc_attr( 'email-subject-' . $email_title ),

                    'label'     =>  esc_attr__( 'Email Subject', 'sdweddingdirectory-venue' ),

                    'std'       =>  'Your Venue will Expired Tomorrow!',

                    'type'      =>  esc_attr( 'text' ),

                    'section'   =>  esc_attr( $have_section ),
                ),

                array(

                  'id'          =>  esc_attr( 'email-body-' . $email_title ),

                  'label'       =>  esc_attr__( 'Email Body Content', 'sdweddingdirectory-venue' ),

                  'type'        =>  esc_attr( 'textarea-simple' ),

                  'std'         =>  '<p>Hello {{vendor_username}},</p><p>Your venues expired tomorrow. Please select your pricing plan to extends your plan.</p><p><a href="{{vendor_login_redirect_link_pricing}}" style="{{primary_button_style}}">Vendor Pricing Package</a></p><p>Thank you.</p>',

                  'section'     =>  esc_attr( $have_section ),

                  'rows'        =>  absint( '4' ),
                ),
            );

            /**
             *  Merge Admin Email Setting
             *  -------------------------
             */
            return      array_merge( $have_setting, array_merge(

                            $add_setting,   parent:: sdweddingdirectory_setting_option_admin_emails( $email_title, $have_section ),

                        ) );
        }

        /**
         *   Setting : New Register Vendor
         *   -----------------------------
         */
        public static function vendor_venue_expire_today( $have_setting = [], $have_section = '' ){

            $email_title    =   esc_attr( 'venue-expired-today' );

            $add_setting    =   array(

                array(

                    'id'          => esc_attr( 'email-tab-' . $email_title ),

                    'label'     =>  esc_attr__( 'Venue Expired Today', 'sdweddingdirectory-venue' ),

                    'type'      =>  esc_attr( 'tab' ),

                    'section'   =>  esc_attr( $have_section ),
                ),

                array(

                    'id'          =>  esc_attr( 'email-subject-' . $email_title ),

                    'label'     =>  esc_attr__( 'Email Subject', 'sdweddingdirectory-venue' ),

                    'std'       =>  'Venue Expired!',

                    'type'      =>  esc_attr( 'text' ),

                    'section'   =>  esc_attr( $have_section ),
                ),

                array(

                  'id'          =>  esc_attr( 'email-body-' . $email_title ),

                  'label'       =>  esc_attr__( 'Email Body Content', 'sdweddingdirectory-venue' ),

                  'type'        =>  esc_attr( 'textarea-simple' ),

                  'std'         =>  '<p>Hello {{vendor_username}},</p><p>Your venues expired. Please purchase your favorite pricing plan to activate venues.</p><p><a href="{{vendor_login_redirect_link_pricing}}" style="{{primary_button_style}}">Vendor Pricing Package</a></p><p>Thank you.</p>',

                  'section'     =>  esc_attr( $have_section ),

                  'rows'        =>  absint( '4' ),
                ),
            );

            /**
             *  Merge Admin Email Setting
             *  -------------------------
             */
            return      array_merge( $have_setting, array_merge(

                            $add_setting,   parent:: sdweddingdirectory_setting_option_admin_emails( $email_title, $have_section ),

                        ) );
        }
    }

    /**
     *  SDWeddingDirectory - Framework - Section - Venue Email
     *  ------------------------------------------------
     */
    SDWeddingDirectory_FrameWork_Venue_Email_Section::get_instance();
}
