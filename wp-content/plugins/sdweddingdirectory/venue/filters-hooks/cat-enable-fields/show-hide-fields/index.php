<?php
/**
 *  SDWeddingDirectory - Category wise enable fields
 *  ----------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Category_Wise_Enable_Disable_Fields' ) && class_exists( 'SDWeddingDirectory_Venue_Category_Select_Event' ) ){

    /**
     *  SDWeddingDirectory - Category wise enable fields
     *  ----------------------------------------
     */
    class SDWeddingDirectory_Venue_Category_Wise_Enable_Disable_Fields extends SDWeddingDirectory_Venue_Category_Select_Event{

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
             *  Badge Filters
             *  --------------
             */
            add_filter( 'sdweddingdirectory/venue-type/show-hide/fields', [ $this, 'show_hide_fields' ], absint( '10' ), absint( '2' ) );

            /**
             *  Badge Filters
             *  --------------
             */
            add_filter( 'sdweddingdirectory/venue-type/show-hide/fields', [ $this, 'term_group_show_hide_fields' ], absint( '20' ), absint( '2' ) );
        }

        /**
         *  Collection of Venue Category Wise - Show / Hide Fields
         *  --------------------------------------------------------
         */
        public static function show_hide_fields( $collection = [], $args = [] ){

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
                         *  Term Post ID
                         *  ------------
                         */
                        $term_post_id       =       esc_attr( $taxonomy .'_'. $term_id );

                        /**
                         *  Merge Show / Hide Basic Fields
                         *  ------------------------------
                         */
                        return      array_merge( $collection, [

                                        'venue_seat_capacity'     =>  sdwd_get_term_field( 'capacity_available', absint( $term_id ) ),

                                        'pricing_available'         =>  sdwd_get_term_field( 'pricing_available', absint( $term_id ) ),

                                        'venue_setting'           =>  sdwd_get_term_field( 'setting_available', absint( $term_id ) ),

                                        'venue_amenities'         =>  sdwd_get_term_field( 'amenities_available', absint( $term_id ) ),

                                        'venue_menu'              =>  sdwd_get_term_field( 'cuisine_offer', absint( $term_id ) ),

                                        'venue_facilities'        =>  sdwd_get_term_field( 'room_facilities', absint( $term_id ) ),

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
         *  Venue Category Wise Enable Disable Term Box Group
         *  ---------------------------------------------------
         */
        public static function term_group_show_hide_fields( $collection = [], $args = [] ){

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
                             *  Enable Section
                             *  --------------
                             */
                            $enable_section         =       sdwd_get_term_field( sanitize_key( 'enable_' . $slug  ), absint( $term_id ) );

                            /**
                             *  Make sure have data
                             *  -------------------
                             */
                            if( $enable_section ){

                                $handler[ $slug ]    =      true;
                            }

                            else{

                                $handler[ $slug ]    =      false;
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
    SDWeddingDirectory_Venue_Category_Wise_Enable_Disable_Fields:: get_instance();
}
