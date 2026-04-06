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
 *  SDWeddingDirectory - Framework - Section - Email Section
 *  ------------------------------------------------
 */
if (    !   class_exists( 'SDWeddingDirectory_FrameWork_Claim_Email_Setting' )      &&

            class_exists( 'SDWeddingDirectory_FrameWork_Vendor_Email_Section' )     &&

            class_exists( 'SDWeddingDirectory_FrameWork_Admin_Email_Section' )      ){

    /**
     *  SDWeddingDirectory - Framework - Section - Email Section
     *  ------------------------------------------------
     */
    class SDWeddingDirectory_FrameWork_Claim_Email_Setting extends SDWeddingDirectory_FrameWork {

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
         *  Constructor
         *  -----------
         */
        public function __construct() {

            return; // Disabled - migrated to native settings

            /**
             *  1. Vendor Email Setting - Claim Venue
             *  ---------------------------------------
             */
            add_filter( SDWeddingDirectory_FrameWork_Vendor_Email_Section:: section_info(), [ $this, 'vendor_email' ], absint( '40' ), absint( '2' ) );

            /**
             *  1. Admin Email Setting - Claim Venue
             *  --------------------------------------
             */
            add_filter(  SDWeddingDirectory_FrameWork_Admin_Email_Section:: section_info(), [ $this, 'admin_email' ], absint( '30' ), absint( '2' ) );
        }

        /**
         *  1. Admin Email Setting - Claim Venue ( Approved / Reject )
         *  ------------------------------------------------------------
         */
        public static function vendor_email( $have_setting = [], $have_section = '' ){

            /**
             *  Email ID
             *  --------
             */
            $email_title    =   esc_attr( 'vendor-claim' );

            /**
             *  Setting Args
             *  ------------
             */
            $add_setting    =   array(

                                    array(

                                        'id'          => esc_attr( 'email-tab-' . $email_title ),

                                        'label'     =>  esc_attr__( 'Claim Venue', 'sdweddingdirectory-claim-venue' ),

                                        'type'      =>  esc_attr( 'tab' ),

                                        'section'   =>  esc_attr( $have_section ),
                                    ),

                                    array(

                                        'id'          =>  esc_attr( 'email-subject-' . $email_title ),

                                        'label'     =>  esc_attr__( 'Email Subject', 'sdweddingdirectory-claim-venue' ),

                                        'std'       =>  esc_attr__( 'Your claim venue status {{claim_venue_status}}.', 'sdweddingdirectory-claim-venue' ),

                                        'type'      =>  esc_attr( 'text' ),

                                        'section'   =>  esc_attr( $have_section ),
                                    ),

                                    array(

                                      'id'          =>  esc_attr( 'email-body-' . $email_title ),

                                      'label'       =>  esc_attr__( 'Vendor Get Email Body Content', 'sdweddingdirectory-claim-venue' ),

                                      'type'        =>  esc_attr( 'textarea-simple' ),

                                      'std'         =>  'Hello {{vendor_username}},  As your claim venue for {{claim_venue_name}} is {{claim_venue_status}} on {{site_name}}.',

                                      'section'     =>  esc_attr( $have_section ),

                                      'rows'        =>  absint( '4' ),
                                    )
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
         *  1. Admin Email Setting - Claim
         *  -------------------------------
         */
        public static function admin_email( $have_setting = [], $have_section = '' ){

            /**
             *  Email ID
             *  --------
             */
            $email_title    =   esc_attr( 'admin-claim' );

            /**
             *  Setting Args
             *  ------------
             */
            $add_setting    =   array(

                                    array(

                                        'id'          => esc_attr( 'email-tab-' . $email_title ),

                                        'label'     =>  esc_attr__( 'Claim Request', 'sdweddingdirectory-claim-venue' ),

                                        'type'      =>  esc_attr( 'tab' ),

                                        'section'   =>  esc_attr( $have_section ),
                                    ),

                                    array(

                                        'id'          =>  esc_attr( 'email-subject-' . $email_title ),

                                        'label'     =>  esc_attr__( 'Email Subject', 'sdweddingdirectory-claim-venue' ),

                                        'std'       =>  esc_attr( 'New Claim Request Available' ),

                                        'type'      =>  esc_attr( 'text' ),

                                        'section'   =>  esc_attr( $have_section ),
                                    ),

                                    array(

                                      'id'          =>  esc_attr( 'email-body-' . $email_title ),

                                      'label'       =>  esc_attr__( 'Vendor Get Email Body Content', 'sdweddingdirectory-claim-venue' ),

                                      'type'        =>  esc_attr( 'textarea-simple' ),

                                      'std'         =>  '{{vendor_username}} wants to claim for {{venue_name}}',

                                      'section'     =>  esc_attr( $have_section ),

                                      'rows'        =>  absint( '4' ),
                                    )
                                );

            /**
             *  Merge Admin Email Setting
             *  -------------------------
             */
            return      array_merge( $have_setting, $add_setting );
        }
    }

    /**
     *  SDWeddingDirectory - Framework - Section - Email Setting
     *  ------------------------------------------------
     */
    SDWeddingDirectory_FrameWork_Claim_Email_Setting::get_instance();
}
