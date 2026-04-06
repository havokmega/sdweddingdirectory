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
if ( ! class_exists( 'SDWeddingDirectory_FrameWork_Real_Wedding_Setting' ) && class_exists( 'SDWeddingDirectory_FrameWork_Couple_Section' )  ) {

    /**
     *  SDWeddingDirectory - Framework - Section - Couple
     *  ------------------------------------------
     */
    class SDWeddingDirectory_FrameWork_Real_Wedding_Setting extends SDWeddingDirectory_FrameWork_Couple_Section {

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
            add_filter(  parent:: section_info(), [ $this, 'realwedding_setting' ], absint( '50' ), absint( '2' ) );
        }

        /**
         *   RealWedding Setting Tab
         *   -----------------------
         */
        public static function realwedding_setting( $have_setting = [], $have_section = '' ){

            /**
             *  Setting 
             *  -------
             */
            $add_setting                =   [

                array(

                    'id'                =>      esc_attr( 'sdweddingdirectory_realwedding_tab' ),

                    'label'             =>      esc_attr__( 'RealWedding Setting', 'sdweddingdirectory-real-wedding' ),

                    'type'              =>      esc_attr( 'tab' ),

                    'section'           =>      esc_attr( $have_section ),
                ),

                array(

                    'id'                =>    esc_attr( 'realwedding-dress-category' ),

                    'label'             =>    esc_attr__( 'Dress Category', 'sdweddingdirectory-real-wedding' ),

                    'desc'              =>    esc_attr__( 'Select the Dress Category to show on the real wedding page.', 'sdweddingdirectory-real-wedding' ),

                    'std'               =>   '',

                    'type'              =>    esc_attr( 'taxonomy-select' ),

                    'section'           =>    esc_attr( $have_section ),

                    'rows'              =>   '',

                    'post_type'         =>   '',

                    'taxonomy'          =>    esc_attr( 'venue-type' ),

                    'min_max_step'      =>   '',

                    'class'             =>   '',

                    'condition'         =>   '',

                    'operator'          =>   'and'
                ),
            ];

            /**
             *  Setting
             *  -------
             */
            return      array_merge( $have_setting, $add_setting );
        }
    }

    /**
     *  SDWeddingDirectory - Framework - Section - Couple
     *  ------------------------------------------
     */
    SDWeddingDirectory_FrameWork_Real_Wedding_Setting::get_instance();
}
