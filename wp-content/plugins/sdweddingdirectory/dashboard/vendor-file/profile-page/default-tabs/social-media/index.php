<?php
/**
 *  -----------------------------------------------
 *  SDWeddingDirectory - Vendor Profile Tab Fields - Object
 *  -----------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Profile_Social_Meida_Tab' ) && class_exists( 'SDWeddingDirectory_Vendor_Profile_Database' ) ){

    /**
     *  -----------------------------------------------
     *  SDWeddingDirectory - Vendor Profile Tab Fields - Object
     *  -----------------------------------------------
     */
    class SDWeddingDirectory_Vendor_Profile_Social_Meida_Tab extends SDWeddingDirectory_Vendor_Profile_Database{

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
            add_filter( 'sdweddingdirectory/vendor-profile/tabs', function( $args = [] ){

                return  array_merge( $args, [

                            'social-media'        =>  [

                                'id'        =>  esc_attr( parent:: _rand() ),

                                'name'      =>  esc_attr( self:: tab_name() ),

                                'callback'  =>  [ 'class' => __CLASS__, 'member' => 'tab_content' ],

                                'create_form'      =>  [

                                    'form_before'   =>  '',

                                    'form_after'    =>  '',

                                    'form_id'       =>  esc_attr( 'sdweddingdirectory_vendor_social_notification' ),

                                    'form_class'    =>  '',

                                    'button_before' =>  '',

                                    'button_after'  =>  '',

                                    'button_id'     =>  esc_attr( 'vendor_social_media_submit' ),

                                    'button_class'  =>  '',

                                    'button_name'   =>  esc_attr__( 'Update Social Profile','sdweddingdirectory' ),

                                    'security'      =>  esc_attr( 'social_media_update' ),
                                ]
                            ]

                        ] );

            }, absint( '40' ) );
        }

        /**
         *.  Tab Content
         *   -----------
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

                            data-class="SDWeddingDirectory_Vendor_Profile_Database"

                            data-member="get_vendor_social_media"

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

                    ?   parent:: get_vendor_social_media( [ 'post_id' =>  absint( parent:: post_id() ) ] )

                    :   ''
            );
        }
    }

    /**
     *  -----------------------------------------------
     *  SDWeddingDirectory - Vendor Profile Tab Fields - Object
     *  -----------------------------------------------
     */
    SDWeddingDirectory_Vendor_Profile_Social_Meida_Tab::get_instance();
}