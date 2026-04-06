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
if ( ! class_exists( 'SDWeddingDirectory_FrameWork_Wishlist_Setting' ) && class_exists( 'SDWeddingDirectory_FrameWork_Couple_Section' )  ) {

    /**
     *  SDWeddingDirectory - Framework - Section - Couple
     *  ------------------------------------------
     */
    class SDWeddingDirectory_FrameWork_Wishlist_Setting extends SDWeddingDirectory_FrameWork_Couple_Section {

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
            add_filter(  parent:: section_info(), [ $this, 'wishlist_setting' ], absint( '10' ), absint( '2' ) );
        }

        /**
         *   Wishlist Setting
         *   ----------------
         */
        public static function wishlist_setting( $have_setting = [], $have_section = '' ){

            /**
             *  Add Setting
             *  -----------
             */
            $add_setting    =   array(

                    array(

                        'id'      =>    esc_attr( 'sdweddingdirectory_wishlist_setting_tab' ),

                        'label'   =>    esc_attr__( 'Wishlist Setting', 'sdweddingdirectory-wishlist' ),

                        'type'    =>    esc_attr( 'tab' ),

                        'section' =>    esc_attr( $have_section ),
                    ),

                    array(

                        'id'        =>    esc_attr( 'wishlist_icon_bg' ),

                        'label'     =>    esc_attr__( 'Wishlist icon background color', 'sdweddingdirectory-wishlist' ),

                        'type'      =>    esc_attr( 'colorpicker-opacity' ),

                        'section'   =>    esc_attr( $have_section ),

                        'object'    =>    array(

                                                'class'     =>    esc_attr( '.btn-wishlist, .remove-wishlist' ),

                                                'property'  =>    esc_attr( 'background-color' ),
                                            ),
                    ),

                    array(
                        'id'        =>    esc_attr( 'wishlist_icon_bg_hover' ),

                        'label'     =>    esc_attr__( 'Wishlist icon hover background color', 'sdweddingdirectory-wishlist' ),

                        'type'      =>    esc_attr( 'colorpicker-opacity' ),

                        'section'   =>    esc_attr( $have_section ),

                        'object'    =>    array(

                                                'class'     =>    esc_attr( '.btn-wishlist:hover, .remove-wishlist' ),

                                                'property'  =>    esc_attr( 'background-color' ),
                                            ),
                    ),

                    array(

                        'id'        =>    esc_attr( 'wishlist_icon_color' ),

                        'label'     =>    esc_attr__( 'Wishlist icon color', 'sdweddingdirectory-wishlist' ),

                        'type'      =>    esc_attr( 'colorpicker-opacity' ),

                        'section'   =>    esc_attr( $have_section ),

                        'object'    =>    array(

                                                'class'     =>    esc_attr( '.btn-wishlist, .remove-wishlist' ),

                                                'property'  =>    esc_attr( 'color' ),
                                            ),
                    ),

                    array(

                        'id'        =>    esc_attr( 'wishlist_icon_color_hover' ),

                        'label'     =>    esc_attr__( 'Wishlist icon hover color', 'sdweddingdirectory-wishlist' ),

                        'type'      =>    esc_attr( 'colorpicker-opacity' ),

                        'section'   =>    esc_attr( $have_section ),

                        'object'    =>    array(

                                                'class'     =>    esc_attr( '.btn-wishlist:hover, .remove-wishlist' ),

                                                'property'  =>    esc_attr( 'color' ),
                                            ),
                    ),
            );

            /**
             *  Merge Setting
             *  -------------
             */
            return      array_merge( $have_setting, $add_setting );
        }
    }

    /**
     *  SDWeddingDirectory - Framework - Section - Couple
     *  ------------------------------------------
     */
    SDWeddingDirectory_FrameWork_Wishlist_Setting::get_instance();
}