<?php
/**
 *  -------------------------------------------------
 *  SDWeddingDirectory - Couple Wedding Website Tabs - Object
 *  -------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Dashboard_Website_Testimonial' ) && class_exists( 'SDWeddingDirectory_Couple_Dashboard_Website' ) ){

    /**
     *  -------------------------------------------------
     *  SDWeddingDirectory - Couple Wedding Website Tabs - Object
     *  -------------------------------------------------
     */
    class SDWeddingDirectory_Couple_Dashboard_Website_Testimonial extends SDWeddingDirectory_Couple_Dashboard_Website{

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
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  Add Filter
             *  ----------
             */
            add_filter( 'sdweddingdirectory/couple-wedding-website/tabs', [ $this, 'tab' ], absint( '80' ), absint( '1' ) );
        }

        /**
         *  Tab Name
         *  --------
         */
        public static function tab_name(){

            return      esc_attr__( 'Our Thank You\'s', 'sdweddingdirectory-couple-website' );
        }

        /**
         *  Tab ID
         *  ------
         */
        public static function tab_id(){

            return      esc_attr( 'couple_wedding_website_testimonials' );
        }

        /**
         *  Tab
         *  ---
         */
        public static function tab( $args = [] ){

            /**
             *  Tabs
             *  ----
             */
            return  array_merge( $args, [  self:: tab_id()  =>  [

                        'active'            =>      false,

                        'id'                =>      esc_attr( parent:: _rand() ),

                        'name'              =>      self:: tab_name(),

                        'callback'          =>      [ 'class' => __CLASS__, 'member' => 'tab_content' ],

                        'create_form'       =>      [

                            'form_before'       =>      '',

                            'form_after'        =>      '',

                            'form_id'           =>      self:: tab_id(),

                            'form_class'        =>      '',

                            'button_before'     =>      '',

                            'button_after'      =>      '',

                            'button_id'         =>      self:: tab_id() . '-button',

                            'button_class'      =>      '',

                            'button_name'       =>      esc_attr__( 'Save Changes', 'sdweddingdirectory-couple-website' ),

                            'security'          =>      self:: tab_id() . '-security',
                        ]
                    ]

                ] );

        }

        /**
         *  Profile Update
         *  --------------
         */
        public static function tab_content(){

                $_section_class     =   sanitize_html_class( 'couple_testimonial' );

                /**
                 *   Section Information
                 *   -------------------
                 */
                parent:: create_section( array(

                    'field'     =>   array(

                        'field_type'  =>    esc_attr( 'info' ),

                        'title'       =>    self:: tab_name(),

                        'class'       =>    esc_attr( 'mb-0 '. $_section_class )
                    )

                ) );

                /**
                 *  Heading
                 *  -------
                 */
                parent:: create_section( array(

                    /**
                     *  Row Managment
                     *  -------------
                     */
                    'div'     =>   array(

                        'start'     =>  true,

                        'end'       =>  false,

                        'class'     =>  esc_attr( 'card-shadow-body pb-2' )
                    ),

                    /**
                     *  Row Managment
                     *  -------------
                     */
                    'row'     =>   array(

                        'start'       =>   true,

                        'end'         =>   false,
                    ),

                    /**
                     *  Column Managment
                     *  ----------------
                     */
                    'column'     =>   array(

                        'grid'        =>   absint( '12' ),

                        'id'          =>   '',

                        'class'       =>   '',

                        'start'       =>   true,

                        'end'         =>   true,
                    ),

                    /**
                     *  Fields Arguments
                     *  ----------------
                     */
                    'field'    =>  array(

                        'field_type'        =>  esc_attr( 'input' ),

                        'type'              =>  esc_attr( 'input' ),

                        'placeholder'       =>  esc_attr__( 'Add Title', 'sdweddingdirectory-couple-website' ),

                        'name'              =>  esc_attr( 'couple_testimonial_heading' ),

                        'id'                =>  esc_attr( 'couple_testimonial_heading' ),

                        'value'             =>  parent:: website_post_data( sanitize_key( 'couple_testimonial_heading' ) ),
                    )

                ) );

                /**
                 *  Heading
                 *  -------
                 */
                parent:: create_section( array(

                    /**
                     *  Row Managment
                     *  -------------
                     */
                    'row'     =>   array(

                        'start'       =>   false,

                        'end'         =>   false,
                    ),

                    /**
                     *  Column Managment
                     *  ----------------
                     */
                    'column'     =>   array(

                        'grid'        =>   absint( '12' ),

                        'id'          =>   '',

                        'class'       =>   '',

                        'start'       =>   true,

                        'end'         =>   true,
                    ),

                    /**
                     *  Fields Arguments
                     *  ----------------
                     */
                    'field'    =>  array(

                        'field_type'        =>  esc_attr( 'textarea-simple' ),

                        'type'              =>  esc_attr( 'input' ),

                        'placeholder'       =>  esc_attr__( 'Write Description', 'sdweddingdirectory-couple-website' ),

                        'name'              =>  esc_attr( 'couple_testimonial_description' ),

                        'id'                =>  esc_attr( 'couple_testimonial_description' ),

                        'rows'              =>  absint( '3' ),

                        'value'             =>  parent:: website_post_data( sanitize_key( 'couple_testimonial_description' ) ),
                    )

                ) );

                /**
                 *  Heading
                 *  -------
                 */
                parent:: create_section( array(

                    /**
                     *  Row Managment
                     *  -------------
                     */
                    'row'     =>   array(

                        'start'       =>   false,

                        'end'         =>   true,
                    ),

                    /**
                     *  Column Managment
                     *  ----------------
                     */
                    'column'     =>   array(

                        'grid'        =>   absint( '12' ),

                        'id'          =>   '',

                        'class'       =>   '',

                        'start'       =>   true,

                        'end'         =>   true,
                    ),

                    /**
                     *  Fields Arguments
                     *  ----------------
                     */
                    'field'    =>  array(

                        'field_type'        =>  esc_attr( 'single_img_upload' ),

                        'value'             =>  parent:: website_post_data( sanitize_key( 'couple_testimonial_image' ) ),

                        'database_key'      =>  esc_attr( 'couple_testimonial_image' ),

                        'image_size'        =>  esc_attr( 'full' ),

                        'is_ajax'           =>  true,

                        'post_id'           =>  absint( parent:: website_post_id() ),

                        'image_size'        =>  esc_attr( 'sdweddingdirectory_img_1400x500' ),

                        'default_img'       =>  esc_url( parent:: placeholder( 'web-layout-1-testimonial' ) ),
                    )

                ) );

                /**
                 *  Collpase
                 *  --------
                 */
                printf( '<div class="pb-3 pt-5 %1$s">

                            <div class="row row-cols-2 %1$s" id="%1$s">%4$s</div>

                            <div class="text-center">

                                <a href="javascript:void(0)" 

                                class="btn btn-primary btn-rounded sdweddingdirectory_couple_website_group_accordion %1$s" 

                                data-class="SDWeddingDirectory_Couple_Website_Database"

                                data-member="get_couple_testimonial"

                                data-parent="%1$s"

                                id="%2$s">%3$s</a>

                            </div>

                        </div>',

                        /**
                         *  1. Parent Collapse ID
                         *  ---------------------
                         */
                        esc_attr( $_section_class ),

                        /**
                         *  2. Section Button ID
                         *  --------------------
                         */
                        esc_attr( parent:: _rand() ),

                        /**
                         *  3. Button Text : Translation Ready
                         *  ----------------------------------
                         */
                        esc_attr__( 'Another Thank You', 'sdweddingdirectory-couple-website' ),

                        /**
                         *  4. Data
                         *  -------
                         */
                        parent:: get_couple_testimonial( [ 'post_id' => parent:: website_post_id() ] )
                );

                ?></div><?php

        }
    }

    /**
     *  -------------------------------------------------
     *  SDWeddingDirectory - Couple Wedding Website Tabs - Object
     *  -------------------------------------------------
     */
    SDWeddingDirectory_Couple_Dashboard_Website_Testimonial::get_instance();
}