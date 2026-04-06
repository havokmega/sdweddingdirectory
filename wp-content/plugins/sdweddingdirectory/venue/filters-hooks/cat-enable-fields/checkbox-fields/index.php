<?php
/**
 *  SDWeddingDirectory - Category wise enable fields
 *  ----------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Category_Wise_Checkbox_Fields' ) && class_exists( 'SDWeddingDirectory_Venue_Category_Select_Event' ) ){

    /**
     *  SDWeddingDirectory - Category wise enable fields
     *  ----------------------------------------
     */
    class SDWeddingDirectory_Venue_Category_Wise_Checkbox_Fields extends SDWeddingDirectory_Venue_Category_Select_Event{

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
          
            if ( ! isset( self::$instance ) ) {

                self::$instance = new self;
            }

            return self::$instance;
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct(){

            /**
             *  Sub Category 
             *  ------------
             */
            add_filter( 'sdweddingdirectory/venue-type/checkbox/fields', [ $this, 'checkbox_fields' ], absint( '10' ), absint( '2' ) );

            /**
             *  Term Group Box
             *  --------------
             */
            add_filter( 'sdweddingdirectory/venue-type/checkbox/fields', [ $this, 'term_group_checkbox_fields' ], absint( '20' ), absint( '2' ) );
        }

        /**
         *  Venue Category Checkbox Wise Fields
         *  --------------------------------------------------------
         */
        public static function checkbox_fields( $collection = [], $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( wp_parse_args( $args, [

                    'term_id'       		=>      absint( '0' ),

                    'post_id'       		=>      absint( '0' ),

                    'taxonomy'      		=>      esc_attr( 'venue-type' ),

					'selected_terms'        =>      [],

                ] ) );

                /**
                 *  Make sure term id not empty!
                 *  ----------------------------
                 */
                if( ! empty( $term_id ) ){

                        /**
                         *  Merge Show / Hide Basic Fields
                         *  ------------------------------
                         */
                        return      array_merge( $collection, [

				                        'venue_sub_category'      =>  self:: sub_category_data( $args ),

                                    ] );
                }
            }

            /**
             *  Default Return - []
             *  -------------------
             */
            return         $collection;
        }

        /**
         *  Get Sub Category Data
         *  ---------------------
         */
        public static function sub_category_data( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( wp_parse_args( $args, [

                    'term_id'       		=>      absint( '0' ),

                    'post_id'       		=>      absint( '0' ),

                    'taxonomy'      		=>      esc_attr( 'venue-type' ),

					'selected_terms'        =>      [],

                ] ) );

                /**
                 *  Make sure term id not empty!
                 *  ----------------------------
                 */
                if( ! empty( $term_id ) ){

                	/**
                	 *  Return Sub Category Data
                	 *  ------------------------
                	 */
                	return		apply_filters( 'sdweddingdirectory/field/checkbox', array(

								    'options'   	=>  apply_filters( 'sdweddingdirectory/term-id/child-data', [

                                                            'taxonomy'        =>      esc_attr( 'venue-type' ),

                                                            'term_id'         =>      $term_id,

                                                        ] ),

								    'selected'  	=>  parent:: _is_array( $selected_terms ) ? array_flip( $selected_terms ) : [],

								    'name'      	=>  esc_attr( 'venue_sub_category' ),

								) );
                }
            }
        }

        /**
         *  Venue Category Checkbox Wise Fields
         *  --------------------------------------------------------
         */
        public static function term_group_checkbox_fields( $collection = [], $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( wp_parse_args( $args, [

                    'term_id'               =>      absint( '0' ),

                    'post_id'               =>      absint( '0' ),

                    'taxonomy'              =>      esc_attr( 'venue-type' ),

                    'selected_terms'        =>      [],

                    'dynamic_data'          =>      apply_filters( 'sdweddingdirectory/dynamic-acf-group-box', [] ),

                    'handler'               =>      []

                ] ) );

                /**
                 *  Make sure term id not empty!
                 *  ----------------------------
                 */
                if( ! empty( $term_id ) ){

                    /**
                     *  Have Dynamic Term Data ?
                     *  ------------------------
                     */
                    if( parent:: _is_array( $dynamic_data ) ){

                        /**
                         *  Loop
                         *  ----
                         */
                        foreach( $dynamic_data as $group_key => $group_data ){

                            /**
                             *  Extract Group Data
                             *  ------------------
                             */
                            extract( $group_data );

                            /**
                             *  Term Post ID
                             *  ------------
                             */
                            $term_post_id           =       esc_attr( $taxonomy ) .'_'. absint( $term_id );

                            /**
                             *  Get Data
                             *  --------
                             */
                            $options                =       apply_filters( 'sdweddingdirectory/term-box-group', [

                                                                'term_id'       =>      absint( $term_id ),

                                                                'slug'          =>      esc_attr( $slug )

                                                            ] );

                            /**
                             *  Make sure have data
                             *  -------------------
                             */
                            if( parent:: _is_array( $options ) ){

                                /**
                                 *  Collection of Data
                                 *  ------------------
                                 */
                                $handler[ $slug ]    =

                                apply_filters( 'sdweddingdirectory/field/checkbox', array(

                                    'options'       =>  $options,

                                    'selected'      =>  in_array( $term_id, $selected_terms )

                                                        ?   get_post_meta( $post_id, $slug, true )

                                                        :   [],

                                    'name'          =>  esc_attr( $slug ),

                                ) );
                            }

                            else{

                                $handler[ $slug ]    =      [];
                            }
                        }
                    }

                    /**
                     *  Merge Show / Hide Basic Fields
                     *  ------------------------------
                     */
                    return      array_merge( $collection, $handler );
                }
            }

            /**
             *  Default Return - []
             *  -------------------
             */
            return         $collection;
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Venue_Category_Wise_Checkbox_Fields:: get_instance();
}