<?php
/**
 *  -------------------------------
 *  SDWeddingDirectory - Dropdown - Filters
 *  -------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Dropdown_Query_String_Hidden_Input' ) && class_exists( 'SDWeddingDirectory_Dropdown_Filters' ) ){

    /**
     *  -------------------------------
     *  SDWeddingDirectory - Dropdown - Filters
     *  -------------------------------
     */
    class SDWeddingDirectory_Dropdown_Query_String_Hidden_Input extends SDWeddingDirectory_Dropdown_Filters {

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
             *  Hidden Fields
             *  -------------
             */
            add_filter( 'sdweddingdirectory/find-venue/hidden-query-inputs', [ $this, 'hidden_inputs_fields' ], absint( '10' ), absint( '1' ) );

            /**
             *  Query Input Fields
             *  ------------------
             */
            add_filter( 'sdweddingdirectory/find-venue/hidden-input', [ $this, 'query_inputs' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Hidden Fields
         *  -------------
         */
        public static function hidden_inputs_fields( $args = [] ){

            /**
             *  Merge Data
             *  ----------
             */
            $hidden_input   =   [
                                    'cat_id'            =>      '',

                                    'state_id'          =>      '',

                                    'location'          =>      '',

                                    'region_id'         =>      '',

                                    'city_id'           =>      '',

                                    'latitude'          =>      '',

                                    'longitude'         =>      '',

                                    'state_name'        =>      '',

                                    'region_name'       =>      '',

                                    'city_name'         =>      '',

                                    'geoloc'            =>      '',

                                    'pincode'           =>      '',
                                ];
            /**
             *  Collection
             *  ----------
             */
            $collection        =       [];

            /**
             *  Have Fields
             *  -----------
             */
            if( parent:: _is_array( $hidden_input ) ){

                foreach( $hidden_input as $key => $value ){

                    /**
                     *  Update Value
                     *  ------------
                     */
                    $collection[ $key ]    =   isset( $_GET[ $key ] ) && ! empty( $_GET[ $key ] ) 

                                            ?   $_GET[ $key ]

                                            :   '';
                }
            }

            /**
             *  Return Data
             *  -----------
             */
            return          array_merge( $args, $collection );
        }


        /**
         *  Hidden Inputs
         *  -------------
         */
        public static function query_inputs( $args = [] ){

            /**
             *  Extract Args
             *  ------------
             */
            extract( wp_parse_args( $args, [

                'handler'           =>      '',

                'hidden_input'      =>      apply_filters( 'sdweddingdirectory/find-venue/hidden-query-inputs', [] ),

                'id'                =>      ''

            ] ) );

            /**
             *  Hidden Input
             *  ------------
             */
            if( parent:: _is_array( $hidden_input ) ){

                foreach( $hidden_input as $key => $value ){

                    $handler   .=

                    sprintf( '<input class="get_query" type="hidden" name="%1$s" value="%2$s" />',

                        /**
                         *  1. Current Venue Layout Showing
                         *  ---------------------------------
                         */
                        esc_attr( $key ),

                        /**
                         *  2. Value
                         *  --------
                         */
                        esc_attr( $value )
                    );
                }
            }

            /**
             *  Hidden Inputs
             *  -------------
             */
            return      sprintf( '<div id="%1$s_hidden_fields">%2$s</div>', $id, $handler );
        }
    }

    /**
     *  -------------------------------
     *  SDWeddingDirectory - Dropdown - Filters
     *  -------------------------------
     */
    SDWeddingDirectory_Dropdown_Query_String_Hidden_Input::get_instance();
}
