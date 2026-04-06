<?php
/**
 *  -------------------------------------------------
 *  SDWeddingDirectory - Couple Wedding Website Tabs - Object
 *  -------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Dashboard_Website_Media' ) && class_exists( 'SDWeddingDirectory_Couple_Dashboard_Website' ) ){

    /**
     *  -------------------------------------------------
     *  SDWeddingDirectory - Couple Wedding Website Tabs - Object
     *  -------------------------------------------------
     */
    class SDWeddingDirectory_Couple_Dashboard_Website_Media extends SDWeddingDirectory_Couple_Dashboard_Website{

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
            add_filter( 'sdweddingdirectory/couple-wedding-website/tabs', [ $this, 'tab' ], absint( '90' ), absint( '1' ) );
        }

        /**
         *  Tab Name
         *  --------
         */
        public static function tab_name(){

            return      esc_attr__( 'Gallery', 'sdweddingdirectory-couple-website' );
        }

        /**
         *  Tab ID
         *  ------
         */
        public static function tab_id(){

            return      esc_attr( 'couple_wedding_website_gallery' );
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

            $_section_class     =   sanitize_html_class( 'couple_gallery' );

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

                    'placeholder'       =>  esc_attr__( 'The Couple', 'sdweddingdirectory-couple-website' ),

                    'name'              =>  esc_attr( 'couple_gallery_heading' ),

                    'id'                =>  esc_attr( 'couple_gallery_heading' ),

                    'value'             =>  parent:: website_post_data( sanitize_key( 'couple_gallery_heading' ) ),
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

                    'class'       =>   sanitize_html_class( 'mb-3' ),

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

                    'name'              =>  esc_attr( 'couple_gallery_description' ),

                    'id'                =>  esc_attr( 'couple_gallery_description' ),

                    'rows'              =>  absint( '3' ),

                    'value'             =>  parent:: website_post_data( sanitize_key( 'couple_gallery_description' ) ),
                )

            ) );

            /**
             *  Collpase
             *  --------
             */
            printf( '<div class="pb-3 %1$s">

                        <div class="row row-cols-1 %1$s" id="%1$s">%4$s</div>

                        <div class="text-center">

                            <a href="javascript:void(0)" 

                            class="btn btn-primary btn-rounded sdweddingdirectory_couple_website_group_accordion %1$s" 

                            data-class="SDWeddingDirectory_Couple_Website_Database"

                            data-member="get_couple_gallery"

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
                    esc_attr__( 'Add New Gallery', 'sdweddingdirectory-couple-website' ),

                    /**
                     *  4. Data
                     *  -------
                     */
                    parent:: get_couple_gallery( [ 'post_id' => parent:: website_post_id() ] )
            );


            ?></div></div><?php
        }
    }

    /**
     *  -------------------------------------------------
     *  SDWeddingDirectory - Couple Wedding Website Tabs - Object
     *  -------------------------------------------------
     */
    SDWeddingDirectory_Couple_Dashboard_Website_Media::get_instance();
}