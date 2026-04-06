<?php
/**
 *  ---------------------------
 *  SDWeddingDirectory - Venue Fields
 *  ---------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Fields_Post_Category' ) && class_exists( 'SDWeddingDirectory_Dashboard_Venue_Update' ) ){

    /**
     *  ---------------------------
     *  SDWeddingDirectory - Venue Fields
     *  ---------------------------
     */
    class SDWeddingDirectory_Venue_Fields_Post_Category extends SDWeddingDirectory_Dashboard_Venue_Update{

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

            return  self::$instance;
        }

        /**
         *  Tab ID
         *  ------
         */
        public static function tab_id(){

            return      sanitize_title( self:: tab_name() );
        }

        /**
         *  Tab Name
         *  --------
         */
        public static function tab_name(){

            return      esc_attr__( 'Venue Category', 'sdweddingdirectory-venue' );
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
            add_filter( 'sdweddingdirectory/add-update-venue/tabs', [ $this, 'tab_info' ], absint( '20' ), absint( '1' ) );
        }

        /**
         *  Tab Info
         *  --------
         */
        public static function tab_info( $args = [] ){

            /**
             *  Merge Tabs
             *  ----------
             */
            return      array_merge( $args, [

                            self:: tab_id()         =>      [

                                'active'            =>      false,

                                'id'                =>      esc_attr( parent:: _rand() ),

                                'name'              =>      esc_attr( self:: tab_name() ),

                                'callback'          =>      [ 'class' => __CLASS__, 'member' => 'tab_content' ],
                            ]

                        ] );
        }

        /**
         *  Select Category Field
         *  ---------------------
         */
        public static function tab_content(){

            /**
             *  Post ID
             *  -------
             */
            $post_id        =       parent:: venue_post_ID();

            /**
             *   Section Information
             *   -------------------
             */
            parent:: create_section( array(

                'field'     =>   array(

                    'field_type'  =>   esc_attr( 'info' ),

                    'title'       =>   esc_attr__( 'Venue Category', 'sdweddingdirectory-venue' ),

                    'class'       =>   sanitize_html_class( 'mb-0' )
                )

            ) );

            /**
             *    Venue Categories
             *    ------------------
             */
            parent:: create_section( array(

                /**
                 *  DIV Managment
                 *  -------------
                 */
                'div'        =>  array(

                    'id'          =>   '',

                    'class'       =>   sanitize_html_class( 'card-body' ),

                    'start'       =>   true,

                    'end'         =>   true,
                ),

                /**
                 *  Row Managment
                 *  -------------
                 */
                'row'        =>  array(

                    'id'          =>   '',

                    'class'       =>   '',

                    'start'       =>   true,

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
                'field'             =>  array(

                    'field_type'    =>  esc_attr( 'select' ),

                    'id'            =>  esc_attr( 'venue_category' ),

                    'name'          =>  esc_attr( 'venue_category' ),

                    'options'       =>  self:: _have_options( $post_id ),

                    'require'       =>  true
                )

            ) );

            /**
             *   Section Information
             *   -------------------
             */
            parent:: create_section( array(

                'field'     =>   array(

                    'field_type'  =>    esc_attr( 'info' ),

                    'class'       =>    esc_attr( 'mb-0 ' . self:: _seating_target( 'label' ) . ' ' . self:: _seating_enable( $post_id ) ),

                    'title'       =>    esc_attr__( 'Seat Capacity', 'sdweddingdirectory-venue' )
                )

            ) );

            /**
             *    Venue Sub Category
             *    --------------------
             */
            parent:: create_section( array(

                /**
                 *  DIV Managment
                 *  -------------
                 */
                'div'        =>  array(

                    'id'          =>   '',

                    'class'       =>   esc_attr( 'card-body ' . self:: _seating_target( 'section' ). ' ' . self:: _seating_enable( $post_id ) ),

                    'start'       =>   true,

                    'end'         =>   true,
                ),

                /**
                 *  Row Managment
                 *  -------------
                 */
                'row'       =>  array(

                    'start'     =>  true,

                    'end'       =>  true,
                ),

                /**
                 *  Column Managment
                 *  ----------------
                 */
                'column'     =>   array(

                    'grid'        =>   absint( '12' ),

                    'start'       =>   true,

                    'end'         =>   true,

                    'class'       =>   sanitize_html_class( 'section-data' ),
                ),

                /**
                 *  Fields Arguments
                 *  ----------------
                 */
                'field'    =>  array(

                    'field_type'        =>  esc_attr( 'input' ),

                    'type'              =>  esc_attr( 'number' ),

                    'formgroup'         =>  false,

                    'placeholder'       =>  esc_attr__( 'Seat Capacity', 'sdweddingdirectory-venue' ),

                    'name'              =>  esc_attr( 'venue_seat_capacity' ),

                    'id'                =>  esc_attr( 'venue_seat_capacity' ),

                    'value'             =>  !   empty( $post_id )

                                            ?   self:: seating_capacity_value( $post_id )

                                            :   ''
                )

            ) );


            /**
             *   Section Information
             *   -------------------
             */
            parent:: create_section( array(

                'field'     =>   array(

                    'field_type'  =>    esc_attr( 'info' ),

                    'class'       =>    esc_attr( 'mb-0 ' . self:: _sub_category_target( 'label' ) . ' ' . self:: _sub_category_enable( $post_id ) ),

                    'title'       =>    self:: _name()
                )

            ) );

            /**
             *    Venue Sub Category
             *    --------------------
             */
            parent:: create_section( array(

                /**
                 *  DIV Managment
                 *  -------------
                 */
                'div'        =>  array(

                    'id'          =>   '',

                    'class'       =>   esc_attr( 'card-body ' . self:: _sub_category_target( 'section' ) . ' ' . self:: _sub_category_enable( $post_id ) ),

                    'start'       =>   true,

                    'end'         =>   true,
                ),

                /**
                 *  Row Managment
                 *  -------------
                 */
                'row'       =>  array(

                    'start'     =>  true,

                    'end'       =>  true,

                    'class'     =>  'section-data row-cols-xl-3 row-cols-lg-3 row-cols-sm-2 row-cols-1'
                ),

                /**
                 *  Fields Arguments
                 *  ----------------
                 */
                'field'      =>   array(

                    'field_type'        =>  esc_attr( 'checkbox' ),

                    'name'          =>      esc_attr( 'venue_sub_category' ),

                    'id'            =>      sanitize_html_class( 'venue_sub_category' ),

                    'class'         =>      sanitize_html_class( 'venue_sub_category' ),

                    'options'       =>      self:: _have_option( $post_id ),

                    'selected'      =>      self:: _have_value( $post_id ),
                )

            ) );

            self:: term_section_load();

        }

        /**
         *  Fields
         *  ------
         */
        public static function term_section_load(){

            /**
             *  Extract Args
             *  ------------
             */
            extract( [

                'post_id'       =>      parent:: venue_post_ID(),

                'term_data'     =>      [],

                'taxonomy'      =>      esc_attr( 'venue-type' ),

            ] );

            /**
             *  Term ID
             *  -------
             */
            $term_id    =   ! empty( $post_id )

                            ?   apply_filters( 'sdweddingdirectory/post/term-parent', [

                                    'post_id'       =>      absint( $post_id ),

                                    'taxonomy'      =>      esc_attr( $taxonomy )
                                ] )

                            :   absint( '0' );

            /**
             *  Settings + Amenities Sections
             *  ------------------------------
             */
            self:: term_option_section( [

                'post_id'           =>  absint( $post_id ),

                'term_id'           =>  absint( $term_id ),

                'taxonomy'          =>  esc_attr( $taxonomy ),

                'slug'              =>  esc_attr( 'venue_setting' ),

                'label'             =>  esc_attr__( 'Setting', 'sdweddingdirectory-venue' ),

                'available_field'   =>  esc_attr( 'setting_available' ),

                'options_field'     =>  esc_attr( 'setting_options' ),
            ] );

            self:: term_option_section( [

                'post_id'           =>  absint( $post_id ),

                'term_id'           =>  absint( $term_id ),

                'taxonomy'          =>  esc_attr( $taxonomy ),

                'slug'              =>  esc_attr( 'venue_amenities' ),

                'label'             =>  esc_attr__( 'Amenities', 'sdweddingdirectory-venue' ),

                'available_field'   =>  esc_attr( 'amenities_available' ),

                'options_field'     =>  esc_attr( 'amenities_options' ),
            ] );

            /**
             *  Collection of Group Box Args
             *  ----------------------------
             */
            $acf_group_box  =   apply_filters( 'sdweddingdirectory/dynamic-acf-group-box', [] );

            /**
             *  Make sure acf group box not empty!
             *  ----------------------------------
             */
            if( parent:: _is_array( $acf_group_box ) ){

                /**
                 *  Loop
                 *  ----
                 */
                foreach( $acf_group_box as $group_box_key => $group_box_data ){

                    /**
                     *  Group Box Data
                     *  --------------
                     */
                    extract( wp_parse_args( $group_box_data, [

                        'display_none'      =>   sanitize_html_class( 'd-none' ),

                        'selected_value'    =>   []

                    ] ) );

                    /**
                     *  Make sure it's not empty!
                     *  -------------------------
                     */
                    if( ! empty( $post_id ) ){

                        /**
                         *  Get Parent Term ID
                         *  ------------------
                         */
                        /**
                         *  Make sure term id not empty!
                         *  ----------------------------
                         */
                        if( ! empty( $term_id ) ){

                            /**
                             *  Enable Section
                             *  --------------
                             */
                            $enable_section   =   sdwd_get_term_field(

                                                        /**
                                                         *  1. Term Key
                                                         *  -----------
                                                         */
                                                        sanitize_key( 'enable_' . $slug  ),

                                                        /**
                                                         *  2. Term ID
                                                         *  ----------
                                                         */
                                                        absint( $term_id )
                                                    );

                            /**
                             *  Make sure admin enable this section
                             *  -----------------------------------
                             */
                            if( $enable_section ){

                                /**
                                 *  Get List
                                 *  --------
                                 */
                                $list       =       sdwd_get_term_repeater( $slug, absint( $term_id ) );

                                /**
                                 *  Have Data ?
                                 *  -----------
                                 */
                                if( parent:: _is_array( $list ) ){

                                    /**
                                     *  Term Data Filled
                                     *  ----------------
                                     */
                                    $term_data          =       self:: normalize_term_options( $list, $slug );

                                    /**
                                     *  Post Data
                                     *  ---------
                                     */
                                    $meta_value         =       get_post_meta( $post_id, sanitize_key( $slug ), true );

                                    /**
                                     *  Have Term Meta ?
                                     *  ----------------
                                     */
                                    if( parent:: _is_array( $term_data ) && parent:: _is_array( $meta_value ) ){

                                        foreach( $meta_value as $meta_key => $term_name ){

                                            $key = ! empty( $meta_key ) ? $meta_key : $term_name;

                                            if( isset( $term_data[ $key ] ) ){

                                                $selected_value[ $key ]   =   $term_data[ $key ];
                                            }
                                        }
                                    }

                                    /**
                                     *  Display Section
                                     *  ---------------
                                     */
                                    $display_none       =       sanitize_html_class( 'd-block' );
                                }
                            }
                        }
                    }

                    /**
                     *   Section Information
                     *   -------------------
                     */
                    parent:: create_section( array(

                        'field'     =>   array(

                            'field_type'  =>    esc_attr( 'info' ),

                            'class'       =>    parent:: _class( [ 'mb-0', $slug . '-label ', $display_none ] ),

                            'title'       =>    esc_attr( $name )
                        )

                    ) );

                    /**
                     *  Selection Data
                     *  --------------
                     */
                    parent:: create_section( array(

                        /**
                         *  DIV Managment
                         *  -------------
                         */
                        'div'        =>  array(

                            'id'          =>   '',

                            'class'       =>   parent:: _class( [ 'card-body', $slug . '-section ', $display_none ] ),

                            'start'       =>   true,

                            'end'         =>   true,
                        ),

                        /**
                         *  Row Managment
                         *  -------------
                         */
                        'row'       =>  array(

                            'start'     =>  true,

                            'end'       =>  true,

                            'class'     =>  parent:: _class( [ 'section-data','row-cols-xl-3','row-cols-lg-3','row-cols-sm-2','row-cols-1', 'term-data' ] ),

                            'id'        =>  $slug,
                        ),

                        /**
                         *  Fields Arguments
                         *  ----------------
                         */
                        'field'      =>   array(

                            'field_type'    =>      esc_attr( 'checkbox' ),

                            'id'            =>      '',

                            'name'          =>      esc_attr( $slug ),

                            'class'         =>      sanitize_html_class( $slug ),

                            'options'       =>      $term_data,

                            'selected'      =>      $selected_value
                        )

                    ) );
                }
            }
        }

        /**
         *  Section Name
         *  ------------
         */
        public static function _name(){

            return  esc_attr__( 'Venue Sub Categories', 'sdweddingdirectory-venue' );
        }

        /**
         *  Normalize Term Options (Label/Value or Legacy)
         *  ----------------------------------------------
         */
        public static function normalize_term_options( $list = [], $slug = '' ){

            if( parent:: _is_array( $list ) ){

                $first = reset( $list );

                if( parent:: _is_array( $first ) && isset( $first['label'], $first['value'] ) ){

                    $options = [];

                    foreach( $list as $row ){

                        if( ! empty( $row['value'] ) ){

                            $options[ $row['value'] ] = ! empty( $row['label'] ) ? $row['label'] : $row['value'];
                        }
                    }

                    return $options;
                }

                return array_column( $list, $slug );
            }

            return [];
        }

        /**
         *  Term Options Section (Settings / Amenities)
         *  -------------------------------------------
         */
        public static function term_option_section( $args = [] ){

            extract( wp_parse_args( $args, [

                'post_id'           =>  absint( '0' ),

                'term_id'           =>  absint( '0' ),

                'taxonomy'          =>  esc_attr( 'venue-type' ),

                'slug'              =>  '',

                'label'             =>  '',

                'available_field'   =>  '',

                'options_field'     =>  '',

            ] ) );

            $display_none   =   sanitize_html_class( 'd-none' );

            $term_data      =   [];

            $selected_value =   [];

            if( ! empty( $term_id ) && ! empty( $available_field ) && ! empty( $options_field ) ){

                $term_post_id   =   esc_attr( $taxonomy ) .'_'. absint( $term_id );

                $is_enable      =   sdwd_get_term_field( sanitize_key( $available_field ), absint( $term_id ) );

                if( $is_enable ){

                    $list       =       sdwd_get_term_repeater( sanitize_key( $options_field ), absint( $term_id ) );

                    $term_data  =       self:: normalize_term_options( $list, $slug );

                    if( parent:: _is_array( $term_data ) ){

                        $meta_value     =   get_post_meta( absint( $post_id ), sanitize_key( $slug ), true );

                        if( parent:: _is_array( $meta_value ) ){

                            foreach( $meta_value as $meta_key => $term_name ){

                                $key = ! empty( $meta_key ) ? $meta_key : $term_name;

                                if( isset( $term_data[ $key ] ) ){

                                    $selected_value[ $key ] = $term_data[ $key ];
                                }
                            }
                        }

                        $display_none   =   sanitize_html_class( 'd-block' );
                    }
                }
            }

            if( empty( $slug ) ){

                return;
            }

            /**
             *  Section Heading
             *  ---------------
             */
            parent:: create_section( array(

                'field'     =>   array(

                    'field_type'  =>    esc_attr( 'info' ),

                    'class'       =>    parent:: _class( [ 'mb-0', $slug . '-label ', $display_none ] ),

                    'title'       =>    esc_attr( $label )
                )

            ) );

            /**
             *  Section Options
             *  ---------------
             */
            parent:: create_section( array(

                'div'        =>  array(

                    'id'          =>   '',

                    'class'       =>   parent:: _class( [ 'card-body', $slug . '-section ', $display_none ] ),

                    'start'       =>   true,

                    'end'         =>   true,
                ),

                'row'       =>  array(

                    'start'     =>  true,

                    'end'       =>  true,

                    'class'     =>  parent:: _class( [ 'section-data','row-cols-xl-3','row-cols-lg-3','row-cols-sm-2','row-cols-1', 'term-data' ] ),

                    'id'        =>  $slug,
                ),

                'field'      =>   array(

                    'field_type'    =>      esc_attr( 'checkbox' ),

                    'id'            =>      '',

                    'name'          =>      esc_attr( $slug ),

                    'class'         =>      sanitize_html_class( $slug ),

                    'options'       =>      $term_data,

                    'selected'      =>      $selected_value
                )

            ) );
        }

        /**
         *  Section ID
         *  ----------
         */
        public static function _sub_category_target( $_suffix = '' ){

            return  sanitize_html_class( 'venue_sub_category' . '-' . $_suffix );
        }

        /**
         *  Enable Section ?
         *  ----------------
         */
        public static function _sub_category_enable( $post_id = 0 ){

            $_display_none    =   sanitize_html_class( 'd-none' );

            if( empty( $post_id ) ){

                return  $_display_none;

            }else{

                $_enable    =   self:: _have_option( $post_id );

                if( ! parent:: _is_array( $_enable ) ){

                    return  $_display_none;
                }
            }
        }

        /**
         *  Have Options ?
         *  --------------
         */
        public static function _have_option( $post_id = 0 ){

            /**
             *  Make sure it's not empty!
             *  -------------------------
             */
            if( ! empty( $post_id ) ){

                return      apply_filters( 'sdweddingdirectory/term-id/child-data', [

                                'taxonomy'        =>        esc_attr( 'venue-type' ),

                                'term_id'         =>        apply_filters( 'sdweddingdirectory/post/term-parent', [

                                                                'post_id'       =>      absint( $post_id ),

                                                                'taxonomy'      =>      esc_attr( 'venue-type' )

                                                            ] )

                            ] );
            }else{

                return      [];
            }
        }

        /**
         *  Have Value ?
         *  ------------
         */
        public static function _have_value( $post_id = 0 ){

            /**
             *  Make sure it's not empty!
             *  -------------------------
             */
            if( ! empty( $post_id ) ){

                $_have_data     =   array_flip( wp_get_post_terms(

                                        absint( $post_id  ),

                                        esc_attr('venue-type'),

                                        array( "fields" => "ids", 'orderby' => 'parent' )

                                    ) );


                if( parent:: _is_array( $_have_data ) ){

                    return  $_have_data;

                }else{

                    return  [];
                }
            }
        }

        /**
         *  Have Seat Capacity ?
         *  --------------------
         */
        public static function _seating_enable( $post_id = 0 ){

            $_display_none  =   sanitize_html_class( 'd-none' );

            /**
             *  Have post ID ?
             *  --------------
             */
            if( empty( $post_id ) ){

                return  $_display_none;

            }else{

                /**
                 *  Venue Seat Capacity
                 *  ---------------------
                 */
                $_enable    =   apply_filters( 'sdweddingdirectory/capacity-enable', [

                                    'term_id'       =>      apply_filters( 'sdweddingdirectory/post/term-parent', [

                                                                'post_id'       =>      absint( $post_id ),

                                                                'taxonomy'      =>      esc_attr( 'venue-type' )

                                                            ] )
                                ] );

                if( ! $_enable ){

                    return  $_display_none;
                }
            }
        }
        
        /**
         *  Section ID
         *  ----------
         */
        public static function _seating_target( $_suffix = '' ){

            return  sanitize_html_class( 'venue_seat_capacity' . '-' . $_suffix );
        }

        /**
         *  Have Post ID ?
         *  --------------
         */
        public static function _have_options( $post_id = 0 ){

            return      SDWeddingDirectory_Taxonomy:: create_select_option(

                            /**
                             *  Parent Terms
                             *  ------------
                             */
                            apply_filters( 'sdweddingdirectory/tax/parent', esc_attr( 'venue-type' ) ),

                            /**
                             *  2. First Option
                             *  ---------------
                             */
                            [ esc_attr__( 'Select Category', 'sdweddingdirectory-venue' ) ],

                            /**
                             *  3. Selected Value
                             *  -----------------
                             */
                            !   empty( $post_id )

                            ?   apply_filters( 'sdweddingdirectory/post/term-parent', [

                                    'post_id'       =>      absint( $post_id ),

                                    'taxonomy'      =>      esc_attr( 'venue-type' )

                                ] )

                            :   ''
                        );
        }

        /**
         *  Have Post ID ?
         *  --------------
         */
        public static function seating_capacity_value( $post_id = 0 ){

            /**
             *  Post ID
             *  -------
             */
            if( ! empty( $post_id ) ){

                return  get_post_meta(

                            /**
                             *  1. Get the ID
                             *  -------------
                             */
                            absint( $post_id ),

                            /**
                             *  2. Key
                             *  ------
                             */
                            sanitize_key( 'venue_seat_capacity' ),

                            /**
                             *  3. True
                             *  -------
                             */
                            true
                        );
            }
        }
    }

    /**
     *  ---------------------------
     *  SDWeddingDirectory - Venue Fields
     *  ---------------------------
     */
    SDWeddingDirectory_Venue_Fields_Post_Category::get_instance();
}
