<?php
/**
 *  ----------------------------------------------
 *  SDWeddingDirectory - Couple Real Wedding Tabs - Object
 *  ----------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Dashboard_Realwedding_Wedding_Info_All_Inputs' ) && class_exists( 'SDWeddingDirectory_Dashboard_Realwedding' ) ){

    /**
     *  ----------------------------------------------
     *  SDWeddingDirectory - Couple Real Wedding Tabs - Object
     *  ----------------------------------------------
     */
    class SDWeddingDirectory_Dashboard_Realwedding_Wedding_Info_All_Inputs extends SDWeddingDirectory_Dashboard_Realwedding{

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
            // Removed — Wedding Info tab eliminated.
            // add_filter( 'sdweddingdirectory/couple-real-wedding/tabs', [ $this, 'tab' ], absint( '20' ), absint( '1' ) );
        }

        /**
         *  Tab Name
         *  --------
         */
        public static function tab_name(){

            return      esc_attr__( 'Wedding Info', 'sdweddingdirectory-real-wedding' );
        }

        /**
         *  Tab ID
         *  ------
         */
        public static function tab_id(){

            return      esc_attr( 'couple_real_wedding_info' );
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

                            'button_name'       =>      esc_attr__( 'Save Changes', 'sdweddingdirectory-real-wedding' ),

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

            /**
             *   Section Information
             *   -------------------
             */
            parent:: create_section( array(

                'field'     =>   array(

                    'field_type'  =>    esc_attr( 'info' ),

                    'class'       =>    sanitize_html_class( 'mb-0' ),

                    'title'       =>    self:: tab_name()
                )

            ) );

            ?><div class="card-shadow-body pb-2"><?php

                /**
                 *    Real Wedding : Season
                 *    ---------------------
                 */
                parent:: create_section( array(

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

                        'grid'        =>   absint( '6' ),

                        'id'          =>   '',

                        'start'       =>   true,

                        'end'         =>   true,
                    ),

                    /**
                     *  Fields Arguments
                     *  ----------------
                     */
                    'field'          =>     self:: realwedding_tax( [

                                                'tax'           =>      esc_attr( 'real-wedding-season' ),

                                                'placeholder'   =>      esc_attr__( 'Season ?', 'sdweddingdirectory-real-wedding' )

                                            ] )
                ) );

                /**
                 *  Real Wedding : Community
                 *  ------------------------
                 */
                parent:: create_section( array(

                    /**
                     *  Column Managment
                     *  ----------------
                     */
                    'column'     =>   array(

                        'grid'        =>   absint( '6' ),

                        'id'          =>   '',

                        'start'       =>   true,

                        'end'         =>   true,
                    ),

                    /**
                     *  Fields Arguments
                     *  ----------------
                     */
                    'field'          =>     self:: realwedding_tax( [

                                                'tax'           =>      esc_attr( 'real-wedding-community' ),

                                                'placeholder'   =>      esc_attr__( 'Which Community ?', 'sdweddingdirectory-real-wedding' )

                                            ] )

                ) );

                /**
                 *  Real Wedding : Space Preference
                 *  -------------------------------
                 */
                parent:: create_section( array(

                    /**
                     *  Column Managment
                     *  ----------------
                     */
                    'column'     =>   array(

                        'grid'        =>   absint( '6' ),

                        'id'          =>   '',

                        'start'       =>   true,

                        'end'         =>   true,
                    ),

                    /**
                     *  Fields Arguments
                     *  ----------------
                     */
                    'field'          =>     self:: realwedding_tax( [

                                                'tax'           =>      esc_attr( 'real-wedding-space-preferance' ),

                                                'placeholder'   =>      esc_attr__( 'Space Preference ?', 'sdweddingdirectory-real-wedding' )

                                            ] )

                ) );

                /**
                 *    Real Wedding : Style
                 *    ---------------------
                 */
                parent:: create_section( array(

                    /**
                     *  Column Managment
                     *  ----------------
                     */
                    'column'     =>   array(

                        'grid'        =>   absint( '6' ),

                        'id'          =>   '',

                        'start'       =>   true,

                        'end'         =>   true,
                    ),

                    /**
                     *  Fields Arguments
                     *  ----------------
                     */
                    'field'          =>     self:: realwedding_tax( [

                                                'tax'           =>      esc_attr( 'real-wedding-style' ),

                                                'placeholder'   =>      esc_attr__( 'Which Style ?', 'sdweddingdirectory-real-wedding' )

                                            ] )
                ) );

                /**
                 *    Venue Categories
                 *    ------------------
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

                        'grid'        =>   absint( '6' ),

                        'id'          =>   '',

                        'start'       =>   true,

                        'end'         =>   true,
                    ),

                    /**
                     *  Fields Arguments
                     *  ----------------
                     */
                    'field'          =>     self:: realwedding_tax( [

                                                'tax'           =>      esc_attr( 'real-wedding-category' ),

                                                'placeholder'   =>      esc_attr__( 'Which Categories ?', 'sdweddingdirectory-real-wedding' )

                                            ] )

                ) );

                /**
                 *    Venue Categories
                 *    ------------------
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

                        'grid'        =>   absint( '6' ),

                        'id'          =>   '',

                        'start'       =>   true,

                        'end'         =>   true,
                    ),

                    /**
                     *  Fields Arguments
                     *  ----------------
                     */
                    'field'             =>  array(

                        'field_type'        =>  esc_attr( 'input' ),

                        'formgroup'         =>  false,

                        'type'              =>  esc_attr( 'text' ),

                        'placeholder'       =>  esc_attr__( 'Wedding, Reception, etc..', 'sdweddingdirectory-real-wedding' ),

                        'id'                =>  esc_attr( 'realwedding_function' ),

                        'name'              =>  esc_attr( 'realwedding_function' ),

                        'value'             =>  parent:: get_realwedding_data( sanitize_key( 'realwedding_function' ) ),
                    )

                ) );

                /**
                 *  Multiple Select Fields
                 *  ----------------------
                 */
                parent:: create_section( array(

                    /**
                     *  Row Managment
                     *  -------------
                     */
                    'row'        =>  array(

                        'start'       =>   false,

                        'end'         =>   true,
                    ),

                    /**
                     *  Column Managment
                     *  ----------------
                     */
                    'column'     =>   array(

                        'grid'        =>   absint( '12' ),

                        'start'       =>   true,

                        'end'         =>   true,
                    ),

                    /**
                     *  Fields Arguments
                     *  ----------------
                     */
                    'field'                     =>      array(

                        'field_type'            =>      esc_attr( 'multiple_select' ),

                        'id'                    =>      esc_attr( 'real-wedding-tag' ),

                        'name'                  =>      esc_attr( 'real-wedding-tag' ),

                        'placeholder'           =>      esc_attr__( 'Select Tags', 'sdweddingdirectory-real-wedding' ),

                        'options'               =>      apply_filters( 'sdweddingdirectory/tax/parent', esc_attr( 'real-wedding-tag' ) ),

                        'selected'              =>      (array) wp_get_post_terms(

                                                            absint( parent:: realwedding_post_id() ),

                                                            esc_attr( 'real-wedding-tag' ),

                                                            array( "fields" => "ids", 'orderby' => 'parent' )
                                                        )
                    )

                ) );

            ?></div><?php
        }

        /**
         *  Create Options
         *  --------------
         */
        public static function realwedding_tax( $args = [] ){

            /**
             *  Extract
             *  -------
             */
            extract( wp_parse_args( $args, [

                'tax'           =>      esc_attr( 'real-wedding-category' ),

                'placeholder'   =>      esc_attr__( 'Write placeholder', 'sdweddingdirectory-real-wedding' )

            ] ) );

            /**
             *  Taxonomy
             *  --------
             */
            return      array(

                            'field_type'  =>    esc_attr( 'select' ),

                            'id'          =>    esc_attr( $tax ),

                            'name'        =>    esc_attr( $tax ),

                            'options'     =>    SDWeddingDirectory_Taxonomy:: create_select_option(

                                                    /**
                                                     *  Get List of taxonomy
                                                     *  --------------------
                                                     */
                                                    SDWeddingDirectory_Taxonomy:: get_taxonomy_parent( $tax ),

                                                    /**
                                                     *  2. First Option
                                                     *  ---------------
                                                     */
                                                    array( ''  =>  $placeholder ),

                                                    /**
                                                     *  3. Selected Value
                                                     *  -----------------
                                                     */
                                                    apply_filters( 'sdweddingdirectory/post/term-parent', [

                                                        'post_id'       =>      absint( parent:: realwedding_post_id() ),

                                                        'taxonomy'      =>      esc_attr( $tax )

                                                    ] )
                                                )
                        );
        }

    }

    /**
     *  ----------------------------------------------
     *  SDWeddingDirectory - Couple Real Wedding Tabs - Object
     *  ----------------------------------------------
     */
    SDWeddingDirectory_Dashboard_Realwedding_Wedding_Info_All_Inputs::get_instance();
}