<?php
/**
 *  SDWeddingDirectory - Category wise enable fields
 *  ----------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Category_Wise_Label_Fields' ) && class_exists( 'SDWeddingDirectory_Venue_Category_Select_Event' ) ){

    /**
     *  SDWeddingDirectory - Category wise enable fields
     *  ----------------------------------------
     */
    class SDWeddingDirectory_Venue_Category_Wise_Label_Fields extends SDWeddingDirectory_Venue_Category_Select_Event{

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
             *  Term Group Box
             *  --------------
             */
            add_filter( 'sdweddingdirectory/venue-type/rename-label/fields', [ $this, 'sub_category_label_name_fields' ], absint( '10' ), absint( '2' ) );

            /**
             *  Term Group Box
             *  --------------
             */
            add_filter( 'sdweddingdirectory/venue-type/rename-label/fields', [ $this, 'term_group_label_name_fields' ], absint( '20' ), absint( '2' ) );
        }

        /**
         *  Venue Sub Category Wise Option Name Display
         *  ---------------------------------------------
         */
        public static function sub_category_label_name_fields( $collection = [], $args = [] ){

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

                    'handler'               =>      []

                ] ) );

                /**
                 *  Make sure term id not empty!
                 *  ----------------------------
                 */
                if( ! empty( $term_id ) ){

                    /**
                     *  Term Post ID
                     *  ------------
                     */
                    $term_post_id                           =       esc_attr( $taxonomy ) .'_'. absint( $term_id );

                    /**
                     *  Label Of Section
                     *  ----------------
                     */
                    $handler[ 'venue_sub_category' ]      =       sdwd_get_term_field( sanitize_key( 'venue_category_sub_category_label'  ), absint( $term_id ) );

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

        /**
         *  Venue Category Wise Option Name Display
         *  -----------------------------------------
         */
        public static function term_group_label_name_fields( $collection = [], $args = [] ){

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
                             *  Label Of Section
                             *  ----------------
                             */
                            $handler[ $slug ]       =       sdwd_get_term_field( sanitize_key( 'label_' . $slug  ), absint( $term_id ) );
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
    SDWeddingDirectory_Venue_Category_Wise_Label_Fields:: get_instance();
}