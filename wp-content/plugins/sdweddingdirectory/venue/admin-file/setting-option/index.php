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
 *  SDWeddingDirectory - Framework - Section - Venue
 *  ------------------------------------------
 */
if ( ! class_exists( 'SDWeddingDirectory_FrameWork_Venue_Section' ) && class_exists( 'SDWeddingDirectory_FrameWork' )  ) {

    /**
     *  SDWeddingDirectory - Framework - Section - Venue
     *  ------------------------------------------
     */
    class SDWeddingDirectory_FrameWork_Venue_Section extends SDWeddingDirectory_FrameWork {

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
            return  esc_attr__( 'Venue Setting', 'sdweddingdirectory-venue' );
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
            return  sanitize_title( 'sdweddingdirectory_venue_section' );
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

            return; // Disabled - migrated to native settings

            /**
             *  1. Section - Venue
             *  ---------------------
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

            }, absint( '70' ) );
 
            /**
             *  2. Setting - Venue General Setting
             *  ------------------------------------
             */
            add_filter(  self:: section_info(), [ $this, 'general_venue_setting' ], absint( '10' ), absint( '2' ) );
        }

        /**
         *   Venue General Setting
         *   -----------------------
         */
        public static function general_venue_setting( $have_setting = [], $have_section = '' ){

            /**
             *  Extract Args
             *  ------------
             */
            extract( [

                'handler'   =>      []

            ] );

            /**
             *  Settings
             *  -------
             */
            $handler[]      =   array(

                                    'id'        =>  esc_attr( 'new_venue_status' ),

                                    'label'     =>  esc_attr__('Venue Publish Setting', 'sdweddingdirectory-venue'),

                                    'desc'      =>  esc_attr__('When vendor create venue is publish or under the admin approved after publish ?', 'sdweddingdirectory-venue'),

                                    'std'       =>  esc_attr( 'publish' ),

                                    'type'      =>  esc_attr( 'select' ),

                                    'section'   =>  esc_attr( $have_section ),

                                    'choices'   =>  array(

                                        array(

                                            'value'     =>  absint( '0' ),

                                            'label'     =>  esc_attr__( 'Admin Verify', 'sdweddingdirectory-venue' ),

                                            'src'       =>  '',
                                        ),

                                        array(

                                            'value'     =>  absint( '1' ),

                                            'label'     =>  esc_attr__( 'Auto Approved', 'sdweddingdirectory-venue' ),

                                            'src'       =>  '',
                                        ),
                                    ),
                                );

            $handler[]      =   array(

                                    'id'        =>  esc_attr( '_currencty_possition_' ),

                                    'label'     =>  esc_attr__( 'Currency Possition', 'sdweddingdirectory-venue' ),

                                    'std'       =>  esc_attr( 'left' ),

                                    'type'      =>  esc_attr( 'select' ),

                                    'section'   =>  esc_attr( $have_section ),

                                    'choices'   =>  array(

                                                        array(

                                                            'value'   =>    esc_attr( 'left' ),

                                                            'label'   =>    esc_attr__( 'Left', 'sdweddingdirectory-venue' ),

                                                            'src'     =>    '',
                                                        ),

                                                        array(

                                                            'value'   =>    esc_attr( 'right' ),

                                                            'label'   =>    esc_attr__( 'Right', 'sdweddingdirectory-venue' ),

                                                            'src'     =>    '',
                                                        ),
                                                    ),
                                );

            $handler[]      =   array(

                                    'id'      =>    esc_attr( 'venue_currency_sign' ),

                                    'label'   =>    esc_attr__( 'Venue Currency Sign', 'sdweddingdirectory-venue' ),

                                    'desc'    =>    esc_attr__( 'Please insert your venue currency sign', 'sdweddingdirectory-venue' ),

                                    'std'     =>    esc_attr( '$' ),

                                    'type'    =>    esc_attr( 'text' ),

                                    'section' =>    esc_attr( $have_section ),
                                );

            $handler[]      =   array(

                                    'id'        =>  esc_attr( 'venue_single_page_gallery_layout' ),

                                    'label'     =>  esc_attr__( 'Venue Single Page Gallery Layout', 'sdweddingdirectory-venue' ),

                                    'desc'      =>  esc_attr__( 'Please select Venue Single Page Layout that can be display on front side.', 'sdweddingdirectory-venue' ),

                                    'std'       =>  absint( '4' ),

                                    'type'      =>  esc_attr( 'select' ),

                                    'section'   =>  esc_attr( $have_section ),

                                    'condition' =>  esc_attr( 'venue_single_page_layout:is(venue_slider_gallery)' ),

                                    'choices'   =>  array(

                                                        array(

                                                            'value'   =>   absint( '1' ),

                                                            'label'   =>   esc_attr__('Full Width Venue Gallery', 'sdweddingdirectory-venue'),

                                                            'src'     =>   '',
                                                        ),

                                                        array(

                                                            'value'     =>  absint( '4' ),

                                                            'label'     =>  esc_attr__( 'Four Column Venue Gallery', 'sdweddingdirectory-venue' ),

                                                            'src'       =>  '',
                                                        ),
                                                    ),
                                );

            /**
             *  Merge Settings
             *  --------------
             */
            return      array_merge( $have_setting, $handler );
        }
    }

    /**
     *  SDWeddingDirectory - Framework - Section - Venue
     *  ------------------------------------------
     */
    SDWeddingDirectory_FrameWork_Venue_Section::get_instance();
}
