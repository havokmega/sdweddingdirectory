<?php
/**
 *  -----------------------------------------------
 *  SDWeddingDirectory - Couple Profile Tab Fields - Object
 *  -----------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Profile_Social_Media_Tab' ) && class_exists( 'SDWeddingDirectory_Couple_Profile_Database' ) ){

    /**
     *  -----------------------------------------------
     *  SDWeddingDirectory - Couple Profile Tab Fields - Object
     *  -----------------------------------------------
     */
    class SDWeddingDirectory_Couple_Profile_Social_Media_Tab extends SDWeddingDirectory_Couple_Profile_Database{

        /**
         *  A reference to an instance of this class
         *  ----------------------------------------
         */
        private static $instance;

        /**
         *  Returns an instance of this class.
         *  ----------------------------------
         */
        public static function get_instance() {

            if ( null == self::$instance ) {

                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         *  Tab Name
         *  --------
         */
        public static function tab_name(){

            return      esc_attr__( 'Social Media', 'sdweddingdirectory' );
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  Add Filter
             *  ----------
             */
            add_filter( 'sdweddingdirectory/couple-profile/tabs', [ $this, 'tab_info' ], absint( '40' ), absint( '1' ) );
        }

        /**
         *  Tab Info
         *  --------
         */
        public static function tab_info( $args = [] ){

            return  array_merge( $args, [

                        'social-media'   =>  [

                            'id'        =>  esc_attr( parent:: _rand() ),

                            'name'      =>  esc_attr( self:: tab_name() ),

                            'callback'  =>  [ 'class' => __CLASS__, 'member' => 'tab_content' ],

                            'create_form'      =>  [

                                'form_before'   =>  '',

                                'form_after'    =>  '',

                                'form_id'       =>  esc_attr( 'sdweddingdirectory_couple_social_notification' ),

                                'form_class'    =>  '',

                                'button_before' =>  '',

                                'button_after'  =>  '',

                                'button_id'     =>  esc_attr( 'couple_social_media_submit' ),

                                'button_class'  =>  parent:: _class( [ 'loader', 'sdweddingdirectory-submit'] ),

                                'button_name'   =>  esc_attr__( 'Update Social Profile','sdweddingdirectory' ),

                                'security'      =>  esc_attr( 'social_media_update' ),
                            ]
                        ]

                    ] );
        }

        /**
         *  Social Media
         *  ------------
         */
        public static function tab_content(){

            /**
             *   Section Information
             *   -------------------
             */
            parent:: create_section( array(

                'field'     =>   array(

                    'field_type'  =>    esc_attr( 'info' ),

                    'class'       =>    sanitize_html_class( 'mb-0' ),

                    'title'       =>    esc_attr( self:: tab_name() ),
                )

            ) );

            /**
             *  Social Media
             *  ------------
             */
            printf( '<div class="card-body %1$s">

                        <div class="row row-cols-1 %1$s" id="%1$s">%4$s</div>

                        <div class="text-center">

                            <a href="javascript:void(0)" 

                            class="btn btn-sm btn-primary sdweddingdirectory_core_group_accordion" 

                            data-class="SDWeddingDirectory_Couple_Profile_Database"

                            data-member="get_couple_social_media"

                            data-parent="%1$s"

                            id="%2$s">%3$s</a>

                        </div>

                    </div>',

                    /**
                     *  1. Parent Collapse ID
                     *  ---------------------
                     */
                    esc_attr( 'social_profile' ),

                    /**
                     *  2. Section Button ID
                     *  --------------------
                     */
                    esc_attr( parent:: _rand() ),

                    /**
                     *  3. Button Text : Translation Ready
                     *  ----------------------------------
                     */
                    esc_attr__( 'Add Social Media', 'sdweddingdirectory' ),

                    /**
                     *  4. Data
                     *  -------
                     */
                    parent:: post_id()

                    ?   parent:: get_couple_social_media( [ 'post_id' =>  absint( parent:: post_id() ) ] )

                    :   ''
            );
        }
    }

    /**
     *  -----------------------------------------------
     *  SDWeddingDirectory - Couple Profile Tab Fields - Object
     *  -----------------------------------------------
     */
    SDWeddingDirectory_Couple_Profile_Social_Media_Tab::get_instance();
}