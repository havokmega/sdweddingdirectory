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
if ( ! class_exists( 'SDWeddingDirectory_Couple_FrameWork_Website_Setting' ) && class_exists( 'SDWeddingDirectory_FrameWork_Couple_Section' )  ) {

    /**
     *  SDWeddingDirectory - Framework - Section - Couple
     *  ------------------------------------------
     */
    class SDWeddingDirectory_Couple_FrameWork_Website_Setting extends SDWeddingDirectory_FrameWork_Couple_Section {

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
            add_filter(  parent:: section_info(), [ $this, 'website_setting' ], absint( '50' ), absint( '2' ) );
        }

        /**
         *   RealWedding Setting Tab
         *   -----------------------
         */
        public static function website_setting( $have_setting = [], $have_section = '' ){

            $add_setting    =   array(

                    array(

                        'id'          =>  esc_attr( 'sdweddingdirectory_website_tab' ),

                        'label'       =>  esc_attr__( 'Website Setting', 'sdweddingdirectory-couple-website' ),

                        'type'        =>  esc_attr( 'tab' ),

                        'section'     =>  esc_attr( $have_section ),
                    ),

                    array(

                        'id'        =>  esc_attr( 'sdweddingdirectory_website_copyright' ),

                        'label'     =>  esc_attr__( 'Couple Wedding Website Copyright', 'sdweddingdirectory-couple-website' ),

                        'type'      =>  esc_attr( 'textarea' ),

                        'std'       =>  sprintf( '<div class="copyrights">Copyright © %1$s — Designed by <a href="#">WP-Organic</a></div>',

                                            /**
                                             *  1. Year
                                             *  -------
                                             */
                                            esc_attr( date( 'Y' ) )
                                        ),

                        'section'   =>  esc_attr( $have_section ),
                    )
            );

            return      array_merge( $have_setting, $add_setting );
        }
    }

    /**
     *  SDWeddingDirectory - Framework - Section - Couple
     *  ------------------------------------------
     */
    SDWeddingDirectory_Couple_FrameWork_Website_Setting::get_instance();
}