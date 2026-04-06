<?php
/**
 *   SDWeddingDirectory Couple Profile
 *   -------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Dashboard_Couple_Profile' ) && class_exists( 'SDWeddingDirectory_Couple_Profile_Database' ) ){

    /**
     *  SDWeddingDirectory Couple Profile
     *  -------------------------
     */
    class SDWeddingDirectory_Dashboard_Couple_Profile extends SDWeddingDirectory_Couple_Profile_Database{

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
             *  1. Dashboard Scripts
             *  --------------------
             */
            add_action( 'wp_enqueue_scripts', [ $this, 'sdweddingdirectory_script' ], absint( '90' ) );

            /**
             *  2. Dashboard Page
             *  -----------------
             */
            add_action( 'sdweddingdirectory/couple-dashboard', [ $this, 'dashboard_page' ], absint( '90' ), absint( '1' ) );
        }

        /**
         *  1. Dashboard Scripts
         *  --------------------
         */
        public static function sdweddingdirectory_script(){

            global $post, $wp_query;

            /**
             *  Couple Profile
             *  --------------
             */
            if( parent:: dashboard_page_set( 'my-profile' ) ){

                /**
                 *  Load Script
                 *  -----------
                 */
                wp_enqueue_script( 

                    /**
                     *  1. File Name
                     *  ------------
                     */
                    esc_attr( sanitize_title( __CLASS__ ) ),

                    /**
                     *  2. File Path
                     *  ------------
                     */
                    esc_url(   plugin_dir_url( __FILE__ )   .   'script.js'   ),

                    /**
                     *  3. Load After "JQUERY" Load
                     *  ---------------------------
                     */
                    array( 'jquery', 'toastr' ),

                    /**
                     *  4. Bootstrap - Library Version
                     *  ------------------------------
                     */
                    esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ )   .   'script.js' ) ),

                    /**
                     *  5. Load in Footer
                     *  -----------------
                     */
                    true
                );

                /**
                 *  Load Library Condition
                 *  ----------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/summary-editor', function( $args = [] ){

                    return  array_merge( $args, [

                                'my-profile'   =>  true

                            ] );
                } );

                /**
                 *  Load Library Condition
                 *  ----------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/media-upload', function( $args = [] ){

                    return  array_merge( $args, [

                                'my-profile'   =>  true

                            ] );
                } );
            }
        }

        /**
         *  2. Dashboard Page
         *  -----------------
         */
        public static function dashboard_page( $args = [] ){

            /**
             *  Have Args
             *  ---------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args,[

                    'layout'        =>      absint( '1' ),

                    'post_id'       =>      '',

                    'page'          =>      ''

                ] ) );

                /**
                 *  Make sure this page is couple dashboard page
                 *  --------------------------------------------
                 */
                if( ! empty( $page ) && $page == esc_attr( 'my-profile' )  ){

                    ?><div class="container"><?php

                        /**
                         *  2.1. Page Title
                         *  ---------------
                         */
                        SDWeddingDirectory_Dashboard:: dashboard_page_header(

                            esc_attr__( 'My Profile', 'sdweddingdirectory' )
                        );

                        /**
                         *  My Profile Tabs
                         *  ---------------
                         */
                        $_tabs  =  apply_filters( 'sdweddingdirectory/couple-profile/tabs', [] );

                        /**
                         *  2.2. Load Profile Tabs
                         *  ----------------------
                         */
                        if( parent:: _is_array( $_tabs ) ){

                            parent:: _create_tabs( $_tabs, [ 'structure_layout' => absint( '3' ) ] );
                        }

                    ?></div><?php
                }
            }
        }

        /**
         *  Future Update
         *  -------------
         */
        public static function _future_update(){

            $_list = [];

            /**
             *  Couple Email Notification
             *  -------------------------
             */
            $_list[]    =   array(

                'tab_info'  =>  array(

                    'tab_name'  =>  esc_attr__( 'Email Notifications', 'sdweddingdirectory' ),
                ),

                'action'    =>   array(

                    'class'     =>  __CLASS__,

                    'member'    =>  esc_attr( 'sdweddingdirectory_email_notification_markup' ),
                ),

                'form_data' =>    array(

                    'form_id'   =>  esc_attr( 'sdweddingdirectory_couple_email_notification' ),

                    'btn_id'    =>  esc_attr( 'couple_email_setting_button' ),

                    'btn_name'  =>  esc_attr__('Update Email Setting','sdweddingdirectory'),

                    'nonce'     =>  esc_attr('couple_email_setting'),
                )
            );

            /**
             *  Couple Delete Account
             *  ---------------------
             */
            $_list[]    =   array(

                'tab_info'  =>  array(

                    'tab_name'  =>  esc_attr__( 'Delete Account', 'sdweddingdirectory' ),
                ),

                'action'    =>   array(

                    'class'     =>  __CLASS__,

                    'member'    =>  esc_attr( 'sdweddingdirectory_delete_account_markup' ),
                ),

                'form_data' =>    array(

                    'form_id'   =>  esc_attr( 'sdweddingdirectory_couple_delete_account' ),

                    'btn_id'    =>  esc_attr( 'couple_delete_account_button' ),

                    'btn_name'  =>  esc_attr__('Removed Account ?','sdweddingdirectory'),

                    'nonce'     =>  esc_attr('couple_removed_account_setting'),
                )
            );
        }

        public static function sdweddingdirectory_email_notification_markup(){

            parent:: row_start();

                ?><div class="col-12"><?php esc_html_e( 'Changes your Email Notifications', 'sdweddingdirectory' ); ?></div><?php

            parent:: row_end();
        }

        public static function sdweddingdirectory_delete_account_markup(){

            parent:: row_start();

                ?><div class="col-12"><?php esc_html_e( 'Delete Account', 'sdweddingdirectory' ); ?></div><?php

            parent:: row_end();
        }
    }

    /**
     *  Kicking this off by calling 'get_instance()' method
     *  ---------------------------------------------------
     */
    SDWeddingDirectory_Dashboard_Couple_Profile::get_instance();
}