<?php
/**
 *  -------------------------------------------------
 *  SDWeddingDirectory - Couple Wedding Website Tabs - Object
 *  -------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Dashboard_Website_RSVP' ) && class_exists( 'SDWeddingDirectory_Couple_Dashboard_Website' ) ){

    /**
     *  -------------------------------------------------
     *  SDWeddingDirectory - Couple Wedding Website Tabs - Object
     *  -------------------------------------------------
     */
    class SDWeddingDirectory_Couple_Dashboard_Website_RSVP extends SDWeddingDirectory_Couple_Dashboard_Website{

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
            add_filter( 'sdweddingdirectory/couple-wedding-website/tabs', [ $this, 'tab' ], absint( '50' ), absint( '1' ) );
        }

        /**
         *  Tab Name
         *  --------
         */
        public static function tab_name(){

            return      esc_attr__( 'RSVP', 'sdweddingdirectory-couple-website' );
        }

        /**
         *  Tab ID
         *  ------
         */
        public static function tab_id(){

            return      esc_attr( 'couple_wedding_website_rsvp' );
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

                $_section_class     =   sanitize_html_class( 'couple_rsvp' );

                /**
                 *   Section Information
                 *   -------------------
                 */
                parent:: create_section( array(

                    'field'     =>   array(

                        'field_type'  =>    esc_attr( 'info' ),

                        'title'       =>    esc_attr__( 'Couple RSVP', 'sdweddingdirectory-couple-website' ),

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

                        'placeholder'       =>  esc_attr__( 'Write Heading', 'sdweddingdirectory-couple-website' ),

                        'name'              =>  esc_attr( 'couple_rsvp_heading' ),

                        'id'                =>  esc_attr( 'couple_rsvp_heading' ),

                        'value'             =>  parent:: website_post_data( sanitize_key( 'couple_rsvp_heading' ) ),
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

                        'name'              =>  esc_attr( 'couple_rsvp_description' ),

                        'id'                =>  esc_attr( 'couple_rsvp_description' ),

                        'rows'              =>  absint( '3' ),

                        'value'             =>  parent:: website_post_data( sanitize_key( 'couple_rsvp_description' ) ),
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

                        'class'       =>   sanitize_html_class( 'mb-3' ),

                        'start'       =>   true,

                        'end'         =>   true,
                    ),

                    /**
                     *  Fields Arguments
                     *  ----------------
                     */
                    'field'    =>  array(

                        'field_type'        =>  esc_attr( 'single_img_upload' ),

                        'value'             =>  parent:: website_post_data( sanitize_key( 'couple_rsvp_image' ) ),

                        'database_key'      =>  esc_attr( 'couple_rsvp_image' ),

                        'is_ajax'           =>  true,

                        'post_id'           =>  absint( parent:: website_post_id() ),

                        'default_img'       =>  esc_url( parent:: placeholder( 'web-layout-1-rsvp' ) ),

                        'image_size'        =>  esc_attr( 'sdweddingdirectory_img_1920x1200' ),
                    )

                ) );

                ?></div><?php

        }
    }

    /**
     *  -------------------------------------------------
     *  SDWeddingDirectory - Couple Wedding Website Tabs - Object
     *  -------------------------------------------------
     */
    SDWeddingDirectory_Couple_Dashboard_Website_RSVP::get_instance();
}