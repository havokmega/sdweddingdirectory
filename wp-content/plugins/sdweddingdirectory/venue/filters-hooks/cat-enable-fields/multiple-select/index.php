<?php
/**
 *  SDWeddingDirectory - Multiple Select fields
 *  -----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Category_Wise_Multiple_Select_Fields' ) && class_exists( 'SDWeddingDirectory_Venue_Category_Select_Event' ) ){

    /**
     *  SDWeddingDirectory - Multiple Select fields
     *  -----------------------------------
     */
    class SDWeddingDirectory_Venue_Category_Wise_Multiple_Select_Fields extends SDWeddingDirectory_Venue_Category_Select_Event{

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
             *  Multiple Select Option with Limit
             *  ---------------------------------
             */
            // add_filter( 'sdweddingdirectory/venue-type/multi-select/fields', [ $this, 'multi_select_fields' ], absint( '10' ), absint( '2' ) );
        }

        /**
         *  Venue Category Checkbox Wise Fields
         *  --------------------------------------------------------
         */
        public static function multi_select_fields( $collection = [], $args = [] ){

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
                	return		apply_filters( 'sdweddingdirectory/field/multiple-select', [

                                    'select_limit'      =>      absint( '2' ),

                                    'placeholder'       =>      esc_attr__( 'Select Sub Category', 'sdweddingdirectory-venue' ),

                                    'options'           =>      apply_filters( 'sdweddingdirectory/term-id/child-data', [

                                                                    'taxonomy'        =>      esc_attr( 'venue-type' ),

                                                                    'term_id'         =>      $term_id,

                                                                ] ),

                                    'name'              =>      esc_attr( 'venue_sub_category' ),

                                    'selected'          =>      parent:: _is_array( $selected_terms ) ? array_flip( $selected_terms ) : [],

                                ] );
                }
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Venue_Category_Wise_Multiple_Select_Fields:: get_instance();
}