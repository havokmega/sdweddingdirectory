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
if ( ! class_exists( 'SDWeddingDirectory_FrameWork_Request_Quote_Email_Setting' ) && class_exists( 'SDWeddingDirectory_FrameWork' ) ) {

    /**
     *  SDWeddingDirectory - Framework - Section - Email Section
     *  ------------------------------------------------
     */
    class SDWeddingDirectory_FrameWork_Request_Quote_Email_Setting extends SDWeddingDirectory_FrameWork {

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
             *  Add Setting
             *  -----------
             */
            add_filter( SDWeddingDirectory_FrameWork_Venue_Section:: section_info(), [ $this, 'venue_setting' ], absint( '20' ), absint( '2' ) );

            /**
             *  Add Setting
             *  -----------
             */
            add_filter( SDWeddingDirectory_FrameWork_Vendor_Email_Section:: section_info(), [ $this, 'vendor_email_setting' ], absint( '20' ), absint( '2' ) );

            /**
             *  Add Setting
             *  -----------
             */
            add_filter( SDWeddingDirectory_FrameWork_Couple_Email_Section:: section_info(), [ $this, 'couple_email_setting' ], absint( '20' ), absint( '2' ) );
        }

        /**
         *  1. Vendor Setting - Request Quote
         *  ---------------------------------
         */
        public static function venue_setting( $have_setting = [], $have_section = '' ){

            $add_setting    =   array(

                array(

                    'id'        =>  esc_attr( 'request_quote_approval' ),

                    'label'     =>  esc_attr__( 'Request Quote Approval Via', 'sdweddingdirectory-request-quote' ),

                    'std'       =>  esc_attr( 'publish' ),

                    'desc'      =>  esc_attr( 'If couple submit the request quote direct approval or admin will check rating then manually approval rating post ?' ),

                    'type'      =>  esc_attr( 'select' ),

                    'section'   =>  esc_attr( $have_section ),

                    'choices'   =>  array(

                                        array(

                                            'value'     =>  esc_attr( 'publish' ),

                                            'label'     =>  esc_attr__( 'Auto Approval', 'sdweddingdirectory-request-quote' ),

                                            'src'       =>  '',
                                        ),
                                        array(

                                            'value'     =>  esc_attr( 'pending' ),

                                            'label'     =>  esc_attr__( 'Manual Approval', 'sdweddingdirectory-request-quote' ),

                                            'src'       =>  '',
                                        ),
                                    ),
                ),
            );

            return      array_merge( $have_setting, $add_setting );
        }

        /**
         *  1. Vendor Email Setting - Request Quote
         *  ---------------------------------------
         */
        public static function vendor_email_setting( $have_setting = [], $have_section = '' ){

            $email_title    =   esc_attr( 'vendor-venue-form-request' );

            $add_setting    =   array(

                    array(

                        'id'          => esc_attr( 'email-tab-' . $email_title ),

                        'label'       => esc_attr__('New Venue Request', 'sdweddingdirectory-request-quote'),

                        'type'        => esc_attr( 'tab' ),

                        'section'     => esc_attr( $have_section )
                    ),

                    array(

                        'id'          =>  esc_attr( 'email-subject-' . $email_title ),

                        'label'       =>  esc_attr__( 'Email Subject', 'sdweddingdirectory-request-quote' ),

                        'std'         =>  esc_attr__( 'Your New Request for {{venue_name}}', 'sdweddingdirectory-request-quote' ),

                        'type'        =>  esc_attr( 'text' ),

                        'section'     =>  esc_attr( $have_section ),

                        'rows'        =>  absint( '8' ),
                    ),

                    array(

                      'id'          =>  esc_attr( 'email-body-' . $email_title ),

                      'label'       =>  esc_attr__( 'Vendor Get Email Body Content', 'sdweddingdirectory-request-quote' ),

                      'type'        =>  esc_attr( 'textarea-simple' ),

                      'std'         =>  '<p>Hello {{vendor_username}},</p><p>You have new request for {{venue_name}} venue.</p><p>couple share request details below.</p>{{request_quote_form_fields}}<p>Thank you</p>',

                      'section'     =>  esc_attr( $have_section ),

                      'rows'        =>  absint( '6' ),
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
        public static function couple_email_setting( $have_setting = [], $have_section = '' ){

            $email_title    =   esc_attr( 'couple-request-venue' );

            $add_setting    =   array(

                    array(

                      'id'          => esc_attr( 'email-tab-' . $email_title ),

                      'label'       =>  esc_attr__( 'Couple Request Venue', 'sdweddingdirectory-request-quote' ),

                      'type'        =>  esc_attr( 'tab' ),

                      'section'     =>  esc_attr( $have_section )
                    ),

                    array(

                      'id'          =>  esc_attr( 'email-subject-' . $email_title ),

                      'label'       =>  esc_attr__( 'Email Subject', 'sdweddingdirectory-request-quote' ),

                      'std'         =>  esc_attr__( 'Your Request for {{venue_name}} submited', 'sdweddingdirectory-request-quote' ),

                      'type'        =>  esc_attr( 'text' ),

                      'section'     =>  esc_attr( $have_section ),

                      'rows'        =>  absint( '8' ),
                    ),

                    array(

                      'id'          =>  esc_attr( 'email-body-' . $email_title ),

                      'label'       =>  esc_attr__( 'Couple Get Email Body Content', 'sdweddingdirectory-request-quote' ),

                      'type'        =>  esc_attr( 'textarea-simple' ),

                      'std'         =>  '<p>Hello {{couple_username}},</p><p>Thank you for request with our {{site_name}}.</p><p>We sending your request for vendor as below.</p>{{request_quote_form_fields}}<p>Thank you</p>',

                      'section'     =>  esc_attr( $have_section ),

                      'rows'        =>  absint( '6' ),
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
     *  SDWeddingDirectory - Framework - Section - Email Setting
     *  ------------------------------------------------
     */
    SDWeddingDirectory_FrameWork_Request_Quote_Email_Setting::get_instance();
}
