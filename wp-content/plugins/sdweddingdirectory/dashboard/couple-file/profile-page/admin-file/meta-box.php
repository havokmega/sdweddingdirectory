<?php
/**
 *  SDWeddingDirectory Couple Profile Metabox
 *  ---------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Profile_Meta' ) && class_exists( 'SDWeddingDirectory_Config' ) ){

    /**
     *  SDWeddingDirectory Couple Profile Metabox
     *  ---------------------------------
     */
    class SDWeddingDirectory_Couple_Profile_Meta extends SDWeddingDirectory_Config{

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
             *  1. Couple Post Metabox Setting for SDWeddingDirectory!
             *  ----------------------------------------------
             */
            add_filter('sdweddingdirectory/meta', [ $this, 'sdweddingdirectory_couple_wedding_info_setting' ], absint('10') );

            /**
             *  2. Couple Post Social Media Page Metabox
             *  ----------------------------------------
             */
            add_filter('sdweddingdirectory/meta', [ $this, 'sdweddingdirectory_couple_profile_setting' ], absint('20') );
        }

        /**
         *  Couple Profile Information
         *  --------------------------
         */
        public static function sdweddingdirectory_couple_wedding_info_setting( $args = [] ) {

            $new_args = array(

                'id'        =>  esc_attr('sdweddingdirectory_couple_realwedding_metabox'),

                'title'     =>  esc_attr__('Wedding Information', 'sdweddingdirectory'),

                'pages'     =>  array('couple'),

                'context'   =>  esc_attr( 'normal' ),

                'priority'  =>  esc_attr( 'high' ),

                'fields'    =>  array(

                    array(

                        'label'     =>  esc_attr__('Bride Info', 'sdweddingdirectory'),

                        'id'        =>  esc_attr('couple_post_bride_info_tab'),

                        'type'      =>  esc_attr( 'tab' ),
                    ),

                    array(

                        'id'        =>  esc_attr( 'bride_first_name' ),

                        'label'     =>  esc_attr__('Bride First Name', 'sdweddingdirectory'),

                        'type'      =>  esc_attr( 'text' ),
                    ),

                    array(

                        'id'        =>  esc_attr( 'bride_last_name' ),

                        'label'     =>  esc_attr__('Bride Last Name', 'sdweddingdirectory'),

                        'type'      =>  esc_attr( 'text' ),
                    ),

                    array(

                        'id'        =>  esc_attr( 'bride_image' ),

                        'label'     =>  esc_attr__('Bride Image', 'sdweddingdirectory'),

                        'type'      =>  esc_attr( 'upload' ),

                        'class'     =>  sanitize_html_class( 'ot-upload-attachment-id' ),
                    ),

                    array(

                        'label'     => esc_attr__('Groom Info', 'sdweddingdirectory'),

                        'id'        => esc_attr( 'couple_post_groom_info_tab' ),

                        'type'      => esc_attr( 'tab' ),
                    ),

                    array(

                        'id'        => esc_attr( 'groom_first_name' ),

                        'label'     => esc_attr__('Groom  First Name', 'sdweddingdirectory'),

                        'type'      => esc_attr( 'text' ),
                    ),

                    array(

                        'id'        => esc_attr( 'groom_last_name' ),

                        'label'     => esc_attr__('Groom Last Name', 'sdweddingdirectory'),

                        'type'      => esc_attr( 'text' ),
                    ),

                    array(

                        'id'        => esc_attr( 'groom_image' ),

                        'label'     => esc_attr__('Groom Image', 'sdweddingdirectory'),

                        'type'      => esc_attr( 'upload' ),

                        'class'     => sanitize_html_class( 'ot-upload-attachment-id' ),
                    ),

                    array(

                        'label'     => esc_attr__('Wedding Info', 'sdweddingdirectory'),

                        'id'        => esc_attr('realwedding_information'),

                        'type'      => esc_attr( 'tab' ),
                    ),

                    array(

                        'id'        => esc_attr( 'wedding_date' ),

                        'label'     => esc_attr__('Wedding Date', 'sdweddingdirectory'),

                        'type'      => esc_attr( 'date-picker' ),
                    ),

                    array(

                        'id'        => esc_attr( 'wedding_address' ),

                        'label'     => esc_attr__('Wedding Address', 'sdweddingdirectory'),

                        'type'      => esc_attr( 'text' ),
                    ),
                ),
            );

            return array_merge($args, array($new_args));
        }

        /**
         *  SDWeddingDirectory Couple Post Setting
         *  ------------------------------
         */
        public static function sdweddingdirectory_couple_profile_setting( $args = [] ) {

            $new_args = array(

                'id'        =>  esc_attr( 'SDWeddingDirectory_Config_Information' ),

                'title'     =>  esc_attr__('Couple Information', 'sdweddingdirectory'),

                'pages'     =>  array( 'couple' ),

                'context'   =>  esc_attr( 'normal' ),

                'priority'  =>  esc_attr( 'low' ),

                'fields'    =>  array(

                                    array(

                                        'label'     =>  esc_attr__('About Info', 'sdweddingdirectory'),

                                        'id'        =>  esc_attr('sdweddingdirectory_couple_about_us'),

                                        'type'      =>  esc_attr( 'tab' ),
                                    ),

                                    array(

                                        'id'        =>  esc_attr( 'first_name' ),

                                        'label'     =>  esc_attr__('First Name', 'sdweddingdirectory'),

                                        'type'      =>  esc_attr( 'text' ),
                                    ),

                                    array(

                                        'id'        =>  esc_attr( 'last_name' ),

                                        'label'     =>  esc_attr__('Last Name', 'sdweddingdirectory'),

                                        'type'      =>  esc_attr( 'text' ),
                                    ),

                                    array(

                                        'id'        =>  esc_attr( 'user_contact' ),

                                        'label'     =>  esc_attr__('User Contact', 'sdweddingdirectory'),

                                        'type'      =>  esc_attr( 'text' ),
                                    ),

                                    array(

                                        'id'        =>  esc_attr( 'user_address' ),

                                        'label'     =>  esc_attr__('User Address', 'sdweddingdirectory'),

                                        'type'      =>  esc_attr( 'text' ),
                                    ),

                                    array(

                                        'label'     =>  esc_attr__('Media', 'sdweddingdirectory'),

                                        'id'        =>  esc_attr( 'sdweddingdirectory_couple_media' ),

                                        'type'      =>  esc_attr( 'tab' ),
                                    ),

                                    array(

                                        'id'        =>  esc_attr( 'user_image' ),

                                        'label'     =>  esc_attr__('Profile Image', 'sdweddingdirectory'),

                                        'type'      =>  esc_attr( 'upload' ),

                                        'class'     =>  sanitize_html_class( 'ot-upload-attachment-id' ),
                                    ),

                                    array(

                                        'id'        => esc_attr( 'couple_dashboard_image' ),

                                        'label'     => esc_attr__('Profile Banner', 'sdweddingdirectory'),

                                        'type'      => esc_attr( 'upload' ),

                                        'class'     => sanitize_html_class( 'ot-upload-attachment-id' ),
                                    ),
                                ),
            );

            /**
             *  Merge Data
             *  ----------
             */
            return  array_merge( $args, array( $new_args ) );
        }

    } // end class

    /**
     *  Direct Call by instance it self
     *  -------------------------------
     */
    SDWeddingDirectory_Couple_Profile_Meta::get_instance();
}