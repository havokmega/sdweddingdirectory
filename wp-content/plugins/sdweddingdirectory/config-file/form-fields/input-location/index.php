<?php
/**
 *  SDWeddingDirectory - Fields
 *  -------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Field_Input_Location' ) && class_exists( 'SDWeddingDirectory_Form_Fields' ) ) {

    /**
     *  SDWeddingDirectory - Fields
     *  -------------------
     */
    class SDWeddingDirectory_Field_Input_Location extends SDWeddingDirectory_Form_Fields{

        /**
         *  Member Variable
         *  ---------------
         */
        private static $instance;

        /**
         *  Initiator
         *  ---------
         */
        public static function get_instance() {

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

            /**
             *  Multiple Selection Field
             *  ------------------------
             */
            add_filter( 'sdweddingdirectory/field/input-location', [ $this, 'field' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Multiple Selection Field
         *  ------------------------
         */
        public static function field( $args = [] ){

            /**
             *  Have Data ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Args
                 *  ----
                 */
                $args       =       wp_parse_args( $args, array(

                                        'div'                       =>      [],

                                        'row'                       =>      [],

                                        'column'                    =>      [],

                                        'data'                      =>      '',

                                        'echo'                      =>      false,

                                        'post_type'                 =>      esc_attr( 'venue' ),

                                        'location_value_id'         =>      '',

                                        'location_value_name'       =>      '',

                                        'ajax_load'                 =>      true,

                                        'hide_empty'                =>      false,

                                        'post_type'                 =>      esc_attr( 'venue' ),

                                        'before_input'              =>      '',

                                        'after_input'               =>      '',

                                        'parent_id'                 =>      esc_attr( parent:: _rand() ),

                                        'placeholder'               =>      esc_attr__( 'Select Location', 'sdweddingdirectory' ),

                                        'dropdown_js'               =>      true,

                                        'depth_level'               =>      absint( '3' ),

                                        'display_tab'               =>      esc_attr( 'all' )

                                    ) );
                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( $args );

                /**
                 *  Before Div ?
                 *  ------------
                 */
                $data               .=      self:: _div_start_setup( $args );

                /**
                 *  Create Section
                 *  --------------
                 */
                $data               .=      apply_filters( 'sdweddingdirectory/input-location', [

                                                'input_style'           =>      sanitize_html_class( 'form-dark' ),

                                                'location_id'           =>      $location_value_id,

                                                'value'                 =>      $location_value_name,

                                                'hide_empty'            =>      $hide_empty,

                                                'ajax_load'             =>      $ajax_load,

                                                'post_type'             =>      $post_type,

                                                'before_input'          =>      $before_input,

                                                'after_input'           =>      $after_input,

                                                'parent_id'             =>      $parent_id,

                                                'placeholder'           =>      $placeholder,

                                                'dropdown_js'           =>      $dropdown_js,

                                                'depth_level'           =>      $depth_level,

                                                'display_tab'           =>      $display_tab

                                            ] );

                /**
                 *  After Setup Div Structure 
                 *  -------------------------
                 */
                $data           .=      parent:: _div_end_setup( $args );

                /**
                 *  Print the Code
                 *  --------------
                 */
                if( $echo ){

                    print       $data;
                }

                /**
                 *  Return Code
                 *  -----------
                 */
                else{

                    return      $data;
                }
            }
        }
    }

    /**
     *  SDWeddingDirectory - Fields
     *  -------------------
     */
    SDWeddingDirectory_Field_Input_Location::get_instance();
}