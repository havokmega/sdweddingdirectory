<?php
/**
 *  -----------------------------------------------
 *  SDWeddingDirectory - Vendor Profile Tab Fields - Object
 *  -----------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Profile_Filters_Tab' ) && class_exists( 'SDWeddingDirectory_Vendor_Profile_Database' ) ){

    /**
     *  -----------------------------------------------
     *  SDWeddingDirectory - Vendor Profile Tab Fields - Object
     *  -----------------------------------------------
     */
    class SDWeddingDirectory_Vendor_Profile_Filters_Tab extends SDWeddingDirectory_Vendor_Profile_Database{

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

            return      esc_attr__( 'Service Filtering Options', 'sdweddingdirectory' );
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
            add_filter( 'sdweddingdirectory/vendor-profile/tabs', [ $this, 'get_tabs' ], absint( '25' ), absint( '1' ) );
        }

        /**
         *  Get Tabs
         *  --------
         */
        public static function get_tabs( $args = [] ){

            return  array_merge( $args, [

                        'vendor-filters'        =>  [

                            'id'        =>  esc_attr( parent:: _rand() ),

                            'name'      =>  esc_attr( self:: tab_name() ),

                            'callback'  =>  [ 'class' => __CLASS__, 'member' => 'tab_content' ],

                            'create_form'      =>  [

                                'form_before'   =>  '',

                                'form_after'    =>  '',

                                'form_id'       =>  esc_attr( 'sdweddingdirectory_vendor_filter_profile' ),

                                'form_class'    =>  '',

                                'button_before' =>  '',

                                'button_after'  =>  '',

                                'button_id'     =>  esc_attr( 'vendor_filter_profile_submit' ),

                                'button_class'  =>  '',

                                'button_name'   =>  esc_attr__( 'Update Service Filtering Options','sdweddingdirectory' ),

                                'security'      =>  esc_attr( 'vendor_filter_profile' ),
                            ]
                        ]

                    ] );
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
             *  Post + Term
             *  -----------
             */
            $post_id    =   absint( parent:: post_id() );

            $term_id    =   ! empty( $post_id )

                            ?   apply_filters( 'sdweddingdirectory/post/term-parent', [

                                    'post_id'       =>      absint( $post_id ),

                                    'taxonomy'      =>      esc_attr( 'vendor-category' )
                                ] )

                            :   absint( '0' );

            /**
             *  Vendor Pricing
             *  --------------
             */
            self:: vendor_option_section( [

                'post_id'           =>  absint( $post_id ),

                'term_id'           =>  absint( $term_id ),

                'taxonomy'          =>  esc_attr( 'vendor-category' ),

                'slug'              =>  esc_attr( 'vendor_pricing' ),

                'label'             =>  esc_attr__( 'Pricing', 'sdweddingdirectory' ),

                'available_field'   =>  esc_attr( 'vendor_pricing_available' ),

                'options_field'     =>  esc_attr( 'vendor_pricing_options' ),

                'option_mode'       =>  esc_attr( 'pricing' ),
            ] );

            /**
             *  Vendor Services
             *  ---------------
             */
            self:: vendor_option_section( [

                'post_id'           =>  absint( $post_id ),

                'term_id'           =>  absint( $term_id ),

                'taxonomy'          =>  esc_attr( 'vendor-category' ),

                'slug'              =>  esc_attr( 'vendor_services' ),

                'label'             =>  esc_attr__( 'Services', 'sdweddingdirectory' ),

                'available_field'   =>  esc_attr( 'vendor_services_available' ),

                'options_field'     =>  esc_attr( 'vendor_services_options' ),

                'option_mode'       =>  esc_attr( 'label_value' ),
            ] );

            /**
             *  Vendor Style
             *  ------------
             */
            self:: vendor_option_section( [

                'post_id'           =>  absint( $post_id ),

                'term_id'           =>  absint( $term_id ),

                'taxonomy'          =>  esc_attr( 'vendor-category' ),

                'slug'              =>  esc_attr( 'vendor_style' ),

                'label'             =>  esc_attr__( 'Style', 'sdweddingdirectory' ),

                'available_field'   =>  esc_attr( 'vendor_style_available' ),

                'options_field'     =>  esc_attr( 'vendor_style_options' ),

                'option_mode'       =>  esc_attr( 'label_value' ),
            ] );

            /**
             *  Vendor Specialties
             *  ------------------
             */
            self:: vendor_option_section( [

                'post_id'           =>  absint( $post_id ),

                'term_id'           =>  absint( $term_id ),

                'taxonomy'          =>  esc_attr( 'vendor-category' ),

                'slug'              =>  esc_attr( 'vendor_specialties' ),

                'label'             =>  esc_attr__( 'Specialties', 'sdweddingdirectory' ),

                'available_field'   =>  esc_attr( 'vendor_specialties_available' ),

                'options_field'     =>  esc_attr( 'vendor_specialties_options' ),

                'option_mode'       =>  esc_attr( 'label_value' ),
            ] );
        }

        /**
         *  Normalize Vendor Options
         *  ------------------------
         */
        public static function normalize_vendor_options( $list = [], $mode = '' ){

            if( parent:: _is_array( $list ) ){

                $options = [];

                foreach( $list as $row ){

                    if( $mode === esc_attr( 'pricing' ) ){

                        if( isset( $row['min'], $row['max'] ) ){

                            $key = sprintf( '%1$s-%2$s', $row['min'], $row['max'] );

                            $label = ! empty( $row['label'] ) ? $row['label'] : $key;

                            $options[ $key ] = $label;
                        }
                    }

                    else{

                        if( ! empty( $row['value'] ) ){

                            $options[ $row['value'] ] = ! empty( $row['label'] ) ? $row['label'] : $row['value'];
                        }
                    }
                }

                return $options;
            }

            return [];
        }

        /**
         *  Vendor Options Section
         *  ----------------------
         */
        public static function vendor_option_section( $args = [] ){

            extract( wp_parse_args( $args, [

                'post_id'           =>  absint( '0' ),

                'term_id'           =>  absint( '0' ),

                'taxonomy'          =>  esc_attr( 'vendor-category' ),

                'slug'              =>  '',

                'label'             =>  '',

                'available_field'   =>  '',

                'options_field'     =>  '',

                'option_mode'       =>  esc_attr( 'label_value' ),

            ] ) );

            $display_none   =   sanitize_html_class( 'd-none' );

            $term_data      =   [];

            $selected_value =   [];

            if( ! empty( $term_id ) && ! empty( $available_field ) && ! empty( $options_field ) ){

                $term_post_id   =   esc_attr( $taxonomy ) .'_'. absint( $term_id );

                $is_enable      =   sdwd_get_term_field( sanitize_key( $available_field ), absint( $term_id ) );

                if( $is_enable ){

                    $list       =       sdwd_get_term_repeater( sanitize_key( $options_field ), absint( $term_id ) );

                    $term_data  =       self:: normalize_vendor_options( $list, $option_mode );

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

                    'class'     =>  parent:: _class( [ 'section-data','row-cols-xl-3','row-cols-lg-3','row-cols-sm-2','row-cols-1' ] ),

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

    /**
     *  -----------------------------------------------
     *  SDWeddingDirectory - Vendor Profile Tab Fields - Object
     *  -----------------------------------------------
     */
    SDWeddingDirectory_Vendor_Profile_Filters_Tab::get_instance();
}
