<?php
/**
 *  SDWeddingDirectory - Fields
 *  -------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Field_Select_Option' ) && class_exists( 'SDWeddingDirectory_Form_Fields' ) ) {

    /**
     *  SDWeddingDirectory - Fields
     *  -------------------
     */
    class SDWeddingDirectory_Field_Select_Option extends SDWeddingDirectory_Form_Fields{

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
             *  Setup Checkbox
             *  --------------
             */
            add_filter( 'sdweddingdirectory/field/select-option', [ $this, 'field' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Setup Checkbox
         *  --------------
         */
        public static function field( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Merge Default Args
                 *  ------------------
                 */
                $args       =       wp_parse_args( $args,   [

                                        'div'               =>      [],

                                        'row'               =>      [],

                                        'column'            =>      [],

                                        'id'                =>      '',

                                        'class'             =>      '',

                                        'name'              =>      '',

                                        'options'           =>      [],

                                        'collection'        =>      '',

                                        'echo'              =>      false,

                                        'require'           =>      false,

                                        'formgroup'         =>      true,

                                        'layout'            =>      '',

                                        'label'             =>      '',

                                        'data'              =>      '',

                                        'selected'          =>      ''

                                    ] );
                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( $args );

                /**
                 *  Have Div ?
                 *  ----------
                 */
                $data       .=      parent:: _div_start_setup( $args );

                /**
                 *  Have Options
                 *  ------------
                 */
                if ( parent:: _is_array( $options ) ) {

                    /**
                     *  In Loop
                     *  -------
                     */
                    foreach ( $options as $key => $value ) {

                        $collection     .=

                        sprintf(    '<option %1$s value="%2$s">%3$s</option>',

                                    /**
                                     *  1. Checkbox Checked
                                     *  -------------------
                                     */
                                    !  empty( $selected )  &&  $key    ==   $selected

                                    ?       esc_attr( 'selected' )

                                    :       '',

                                    /**
                                     *  2. Checkbox Key
                                     *  ---------------
                                     */
                                    esc_attr( $key ),

                                    /**
                                     *  3. Value
                                     *  --------
                                     */
                                    esc_attr( $value )
                        );
                    }

                    /**
                     *  Select Option Setup
                     *  -------------------
                     */
                    $data       .=      

                    sprintf('<div class="mb-3">

                                <select id="%1$s" name="%2$s" class="mb20 %1$s %3$s">%4$s</select>
                                
                            </div>',

                        /**
                         *  1. Have ID
                         *  ----------
                         */
                        esc_attr( $id ),

                        /**
                         *  2. Have Name
                         *  ------------
                         */
                        esc_attr( $name ),

                        /**
                         *  3. Have Class ?
                         *  ---------------
                         */
                        $class,

                        /**
                         *  4. Options
                         *  ----------
                         */
                        $collection
                    );

                    /**
                     *  Have Div ?
                     *  ----------
                     */
                    $data           .=      parent:: _div_end_setup( $args );

                    /**
                     *  Is print ?
                     *  ----------
                     */
                    if( $echo ){

                        print           $data;
                    }

                    /**
                     *  Else
                     *  ----
                     */
                    else{

                        return          $data;
                    }
                }
            }
        }
    }

    /**
     *  SDWeddingDirectory - Fields
     *  -------------------
     */
    SDWeddingDirectory_Field_Select_Option::get_instance();
}