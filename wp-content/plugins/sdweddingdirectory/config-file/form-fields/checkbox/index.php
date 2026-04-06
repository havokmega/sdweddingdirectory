<?php
/**
 *  SDWeddingDirectory - Fields
 *  -------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Field_Checkbox' ) && class_exists( 'SDWeddingDirectory_Form_Fields' ) ) {

    /**
     *  SDWeddingDirectory - Fields
     *  -------------------
     */
    class SDWeddingDirectory_Field_Checkbox extends SDWeddingDirectory_Form_Fields{

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
            add_filter( 'sdweddingdirectory/field/checkbox', [ $this, 'field' ], absint( '10' ), absint( '1' ) );
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

                                        'selected'          =>      [],

                                        'echo'              =>      false,

                                        'data'              =>      '',

                                        'counter'           =>      absint( '0' )

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

                        $data   .=

                        sprintf(    '<div class="col">

                                        <div class="mb-3">

                                            <input type="checkbox" class="form-check-input" %1$s data-value="%2$s" value="%3$s" name="%4$s" id="%4$s_%5$s" />

                                            <label class="form-check-label" for="%4$s_%5$s">%6$s</label>

                                        </div>

                                    </div>',

                                    /**
                                     *  1. Checkbox Checked
                                     *  -------------------
                                     */
                                    parent:: _is_array( $selected ) && array_key_exists( $key, $selected )

                                    ?       esc_attr( 'checked' )

                                    :       '',

                                    /**
                                     *  2. Checkbox Key
                                     *  ---------------
                                     */
                                    esc_attr( $key ),

                                    /**
                                     *  3. Checkbox Value
                                     *  -----------------
                                     */
                                    esc_attr( sanitize_title( $value ) ),

                                    /**
                                     *  4. Checkbox Name
                                     *  ----------------
                                     */
                                    esc_attr( $name ),

                                    /**
                                     *  5. Loop Count
                                     *  -------------
                                     */
                                    absint( $counter ),

                                    /**
                                     *  6. Value
                                     *  --------
                                     */
                                    esc_attr( $value )
                        );

                        /**
                         *  Counter
                         *  -------
                         */
                        $counter++;
                    }

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
    SDWeddingDirectory_Field_Checkbox::get_instance();
}