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
 *  SDWeddingDirectory - Framework - Section - Couple Email
 *  -----------------------------------------------
 */
if ( ! class_exists( 'SDWeddingDirectory_FrameWork_RSVP_Setting' ) && class_exists( 'SDWeddingDirectory_FrameWork_Couple_Email_Section' )  ) {

    /**
     *  SDWeddingDirectory - Framework - Section - Couple Email
     *  -----------------------------------------------
     */
    class SDWeddingDirectory_FrameWork_RSVP_Setting extends SDWeddingDirectory_FrameWork_Couple_Email_Section {

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
             *  1. Couple Email Setting - RSVPs
             *  -------------------------------
             */
            add_filter( parent:: section_info(), [ $this, 'guest_rsvp_setting' ], absint( '30' ), absint( '2' ) );

            /**
             *  2. Couple Email Setting - RSVPs
             *  -------------------------------
             */
            add_filter( parent:: section_info(), [ $this, 'couple_request_rsvp' ], absint( '30' ), absint( '2' ) );
        }

        /**
         *  2. Couple Email Setting - Request Quote
         *  ---------------------------------------
         */
        public static function guest_rsvp_setting( $have_setting = [], $have_section = '' ){

            $email_title    =   esc_attr( 'guest-rsvp' );

            $add_setting    =   array(

                array(

                    'id'          => esc_attr( 'email-tab-' . $email_title ),

                    'label'     =>  esc_attr__( 'Guest Submit RSVP', 'sdweddingdirectory-rsvp' ),

                    'type'      =>  esc_attr( 'tab' ),

                    'section'   =>  esc_attr( $have_section ),
                ),

                array(

                    'id'        =>  esc_attr( 'email-subject-' . $email_title ),

                    'label'     =>  esc_attr__( 'Email Subject', 'sdweddingdirectory-rsvp' ),

                    'std'       =>  'RSVP from {{guest_name}}',

                    'type'      =>  esc_attr( 'text' ),

                    'section'   =>  esc_attr( $have_section ),
                ),

                array(

                  'id'          =>  esc_attr( 'email-body-' . $email_title ),

                  'label'       =>  esc_attr__( 'RSVP Email Body Content', 'sdweddingdirectory-rsvp' ),

                  'type'        =>  esc_attr( 'textarea-simple' ),

                  'std'         =>  '<h3>{{groom_firstname}} {{groom_lastname}} & {{bride_firstname}} {{bride_lastname}}</h3><p>{{wedding_date}}</p><p>These guests have confirmed their attendance.</p><p>{{guest_data}}</p><p><a href="{{couple_login_redirect_link_guest-management}}" style="{{primary_button_style}}">View Guest List</a></p>',

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
         *  2. Couple Email Setting - Request Quote
         *  ---------------------------------------
         */
        public static function couple_request_rsvp( $have_setting = [], $have_section = '' ){

            $email_title    =   esc_attr( 'couple-rsvps' );

            $add_setting    =   array(

                array(

                    'id'        => esc_attr( 'email-tab-' . $email_title ),

                    'label'     =>  esc_attr__( 'Couple Send Invitation RSVP', 'sdweddingdirectory-rsvp' ),

                    'type'      =>  esc_attr( 'tab' ),

                    'section'   =>  esc_attr( $have_section ),
                ),

                array(

                    'id'        =>  esc_attr( 'email-subject-' . $email_title ),

                    'label'     =>  esc_attr__( 'Email Subject', 'sdweddingdirectory-rsvp' ),

                    'std'       =>  esc_attr( 'Rsvp requested' ),

                    'type'      =>  esc_attr( 'text' ),

                    'section'   =>  esc_attr( $have_section ),
                ),

                array(

                    'id'        =>  esc_attr( 'email-image-' . $email_title ),

                    'label'     =>  esc_attr__( 'RSVP Image', 'sdweddingdirectory-rsvp' ),

                    'std'       =>  esc_url( plugin_dir_url(__FILE__) . 'images/rsvp.jpg' ),

                    'type'      =>  esc_attr( 'upload' ),

                    'section'   =>  esc_attr( $have_section ),
                ),

                array(

                  'id'          =>  esc_attr( 'email-body-' . $email_title ),

                  'label'       =>  esc_attr__( 'RSVP Email Body Content', 'sdweddingdirectory-rsvp' ),

                  'type'        =>  esc_attr( 'textarea-simple' ),

                  'std'         =>  '<h3>{{first_name}} {{last_name}}</h3><p>{{wedding_date}}</p><p>{{rsvp_image}},</p><p>{{rsvp_content}}</p><p><a href="{{couple_website}}" style="{{primary_button_style}}">RSVP</a></p>',

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
     *  SDWeddingDirectory - Framework - Section - Couple Email
     *  -----------------------------------------------
     */
    SDWeddingDirectory_FrameWork_RSVP_Setting::get_instance();
}