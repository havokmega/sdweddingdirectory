<?php
/**
 *  SDWeddingDirectory - Fields
 *  -------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Field_Multiple_Select' ) && class_exists( 'SDWeddingDirectory_Form_Fields' ) ) {

    /**
     *  SDWeddingDirectory - Fields
     *  -------------------
     */
    class SDWeddingDirectory_Field_Multiple_Select extends SDWeddingDirectory_Form_Fields{

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
            add_filter( 'sdweddingdirectory/field/multiple-select', [ $this, 'field' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Multiple Selection Field
         *  ------------------------
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
                $args       =       wp_parse_args( $args, array(

                                        'div'               =>      [],

                                        'row'               =>      [],

                                        'column'            =>      [],

                                        'id'                =>      esc_attr( parent:: _rand() ),

                                        'class'             =>      '',

                                        'name'              =>      '',

                                        'options'           =>      [],

                                        'echo'              =>      false,

                                        'select_limit'      =>      absint( '0' ),

                                        'placeholder'       =>      esc_attr__( 'Select Options', 'sdweddingdirectory' ),

                                        'data'              =>      '',

                                        'option_collect'    =>      '',

                                        'selected'          =>      [],

                                        'label'             =>      '',

                                        'parent_class'      =>      'mb-3'

                                    ) );
                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( $args );

                /**
                 *  Have Div ?
                 *  ----------
                 */
                $data   .=   parent:: _div_start_setup( $args );

                /**
                 *  Have Options ?
                 *  --------------
                 */
                if( parent:: _is_array( $options ) ){

                    foreach( $options as $key => $value ){

                        /**
                         *  Collect
                         *  -------
                         */
                        $option_collect     .=      sprintf( '<option value="%1$s" %2$s>%3$s</option>',
                                                        
                                                        /**
                                                         *  1. Key
                                                         *  ---
                                                         */
                                                        esc_attr( $key ),

                                                        /**
                                                         *  2. Selected
                                                         *  -----------
                                                         */
                                                        parent:: _is_array( $selected ) && in_array( $key, $selected )

                                                        ?   esc_attr( 'selected' )

                                                        :   '',

                                                        /**
                                                         *  3. Name
                                                         *  -------
                                                         */
                                                        esc_attr( $value )
                                                    );
                    }
                }

                /**
                 *  Select Option Setup
                 *  -------------------
                 */
                $data   .=

                sprintf('<div class="%8$s">

                            %1$s

                            <select id="%2$s" name="%3$s" multiple="multiple" data-placeholder="%4$s" 

                                    class="sdweddingdirectory-light-multiple-select mb20 %5$s" %6$s>

                                    <option></option> 

                                    %7$s

                            </select>

                        </div>',

                        /**
                         *  1. Have Label ?
                         *  ---------------
                         */
                        !   empty( $label )

                        ?   sprintf( '<label class="control-label mb-2">%1$s</label>',

                                /**
                                 *  1. Lable
                                 *  --------
                                 */
                                esc_attr( $label )
                            )

                        :   '',

                        /**
                         *  2. Have ID ?
                         *  ------------
                         */
                        esc_attr( $id ),

                        /**
                         *  3. Have Name ?
                         *  --------------
                         */
                        esc_attr( $name ),

                        /**
                         *  4. Have Placeholder ?
                         *  ---------------------
                         */
                        esc_attr( $placeholder ),

                        /**
                         *  5. Have Class ?
                         *  ---------------
                         */
                        esc_attr( $class ),

                        /**
                         *  6. Selection Limit
                         *  -------------------
                         */
                        $select_limit >= absint( '1' ) && ! empty( $select_limit )

                        ?   sprintf( 'data-selection-limit="%1$s"', absint( $select_limit ) )

                        :   '',

                        /**
                         *  7. Have Option ?
                         *  ----------------
                         */
                        $option_collect,

                        /**
                         *  8. Parent Class
                         *  ---------------
                         */
                        $parent_class
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

    /**
     *  SDWeddingDirectory - Fields
     *  -------------------
     */
    SDWeddingDirectory_Field_Multiple_Select::get_instance();
}