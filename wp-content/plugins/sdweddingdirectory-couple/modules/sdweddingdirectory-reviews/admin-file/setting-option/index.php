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
 *  SDWeddingDirectory - Framework - Section - Style
 *  ----------------------------------------
 */
if (    !   class_exists( 'SDWeddingDirectory_FrameWork_Review_Setting' ) &&

            class_exists( 'SDWeddingDirectory_FrameWork_Style_Section' ) &&

            class_exists( 'SDWeddingDirectory_FrameWork_Venue_Section' )
    ){

    /**
     *  SDWeddingDirectory - Framework - Section - Style
     *  ----------------------------------------
     */
    class SDWeddingDirectory_FrameWork_Review_Setting extends SDWeddingDirectory_FrameWork_Style_Section {

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
            add_filter(  parent:: section_info(), [ $this, 'review_setting' ], absint( '50' ), absint( '2' ) );

            /**
             *  Add Setting
             *  -----------
             */
            add_filter( SDWeddingDirectory_FrameWork_Venue_Section:: section_info(), [ $this, 'venue_setting' ], absint( '30' ), absint( '2' ) );
        }

        /**
         *   Rating : Style Setting Tab
         *   --------------------------
         */
        public static function review_setting( $have_setting = [], $have_section = '' ){

            $add_setting    =   array(

                    array(

                        'id'          =>    esc_attr( 'sdweddingdirectory_review_style_tab' ),

                        'label'       =>    esc_attr__( 'Rating Style', 'sdweddingdirectory-reviews' ),

                        'type'        =>    esc_attr( 'tab' ),

                        'section'     =>    esc_attr( $have_section ),
                    ),

                    array(

                        'id'          =>    esc_attr( 'normalFill' ),

                        'label'       =>    esc_attr__('normalFill', 'sdweddingdirectory-reviews'),

                        'std'         =>    esc_attr( '#d9d5d4' ),

                        'type'        =>    esc_attr( 'colorpicker-opacity' ),

                        'section'     =>    esc_attr( $have_section ),
                    ),

                    array(

                        'id'          =>    esc_attr( 'ratedFill' ),

                        'label'       =>    esc_attr__( 'ratedFill', 'sdweddingdirectory-reviews' ),

                        'std'         =>    esc_attr( '#fabc01' ),

                        'type'        =>    esc_attr( 'colorpicker-opacity' ),

                        'section'     =>    esc_attr( $have_section ),
                    ),
            );

            return      array_merge( $have_setting, $add_setting );
        }

        /**
         *  2. Vendor Setting - Request Quote
         *  ---------------------------------
         */
        public static function venue_setting( $have_setting = [], $have_section = '' ){

            $add_setting    =   array(

                array(

                    'id'        =>  esc_attr( 'rating_approval' ),

                    'label'     =>  esc_attr__( 'Rating Post Approval', 'sdweddingdirectory-reviews' ),

                    'std'       =>  esc_attr( 'pending' ),

                    'desc'      =>  esc_attr( 'If couple write the rating after direct approval or admin will check rating then manually approval rating post ?' ),

                    'type'      =>  esc_attr( 'select' ),

                    'section'   =>  esc_attr( $have_section ),

                    'choices'   =>  array(

                                        array(

                                            'value'     =>  esc_attr( 'publish' ),

                                            'label'     =>  esc_attr__( 'Auto Approval', 'sdweddingdirectory-reviews'),

                                            'src'       =>  '',
                                        ),
                                        array(

                                            'value'     =>  esc_attr( 'pending' ),

                                            'label'     =>  esc_attr__( 'Manual Approval', 'sdweddingdirectory-reviews'),

                                            'src'       =>  '',
                                        ),
                                    ),
                ),                
            );

            return      array_merge( $have_setting, $add_setting );
        }
    }

    /**
     *  SDWeddingDirectory - Framework - Section - Style
     *  ----------------------------------------
     */
    SDWeddingDirectory_FrameWork_Review_Setting::get_instance();
}