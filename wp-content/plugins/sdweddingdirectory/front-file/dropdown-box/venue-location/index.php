<?php
/**
 *  ------------------------------
 *  SDWeddingDirectory - Dropdown - Object
 *  ------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Location_Dropdown_Script' ) && class_exists( 'SDWeddingDirectory_Dropdown_Script' ) ){

    /**
     *  ------------------------------
     *  SDWeddingDirectory - Dropdown - Object
     *  ------------------------------
     */
    class SDWeddingDirectory_Location_Dropdown_Script extends SDWeddingDirectory_Dropdown_Script{

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
             *  Load Category Dropdown Input Fields with Data
             *  ---------------------------------------------
             */
            add_filter( 'sdweddingdirectory/input-location', [ $this, 'input_location_field' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Load Location Input Fields
         *  --------------------------
         */
        public static function input_location_field( $args = [] ){

            /**
             *  Make sure it's array ?
             *  ----------------------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'before_input'      =>      '',

                    'after_input'       =>      '',

                    'input_prefix'      =>      sprintf( '<span class="d-flex align-items-center px-3 before-input-box">%1$s</span>', 

                                                    /**
                                                     *  1. Translation Ready String
                                                     *  ---------------------------
                                                     */
                                                    esc_attr__( 'in', 'sdweddingdirectory' )
                                                ),

                    'dropdown_id'       =>      '',

                    'placeholder'       =>      esc_attr__( 'Location', 'sdweddingdirectory' ),

                    'result_page'       =>      absint( '1' ),

                    'ajax_load'         =>      absint( '1' ),

                    'hide_empty'        =>      absint( '1' ),

                    'value'             =>      '',

                    'input_style'       =>      sanitize_html_class( 'form-light' ),

                    'cat_id'            =>      '',

                    'state_id'          =>      '',

                    'location'          =>      isset( $_GET[ 'location' ] ) && ! empty( $_GET[ 'location' ] )

                                                ?       sanitize_title( wp_unslash( $_GET[ 'location' ] ) )

                                                :       '',

                    'city_id'           =>      '',

                    'region_id'         =>      '',

                    'city_name'         =>      isset( $_GET[ 'city_name' ] ) && ! empty( $_GET[ 'city_name' ]  )

                                                ?       esc_attr( $_GET[ 'city_name' ] )

                                                :       false,

                    'region_name'       =>      isset( $_GET[ 'region_name' ] ) && ! empty( $_GET[ 'region_name' ]  )

                                                ?       esc_attr( $_GET[ 'region_name' ] )

                                                :       false,

                    'state_name'        =>      isset( $_GET[ 'state_name' ] ) && ! empty( $_GET[ 'state_name' ]  )

                                                ?       esc_attr( $_GET[ 'state_name' ] )

                                                :       false,

                    'post_type'         =>      esc_attr( 'venue' ),

                    'taxonomy'          =>      esc_attr( 'venue-location' ),

                    'parent_id'         =>      esc_attr( parent:: _rand() ),

                    'allowed_terms'     =>      [],

                    'location_id'       =>      '',

                    'geoloc'            =>      isset( $_GET[ 'geoloc' ] ) && ! empty( $_GET[ 'geoloc' ]  ) && $_GET[ 'geoloc' ] == absint( '1' )

                                                ?       true 

                                                :       false,

                    'dropdown_js'       =>      true,

                    'depth_level'       =>      apply_filters( 'sdweddingdirectory/input-location/depth-level', absint( '3' ) ),

                    'display_tab'       =>      esc_attr( 'all' )

                ] ) );

                /**
                 *  Make sure GEO Enable in Query String ?
                 *  --------------------------------------
                 *  Then we make filter by term name foudn in datbase
                 *  -------------------------------------------------
                 */
                if( ! empty( $location ) ){

                    $location_obj = get_term_by( 'slug', sanitize_title( $location ), esc_attr( $taxonomy ) );

                    if( ! empty( $location_obj ) && ! is_wp_error( $location_obj ) ){

                        $location_id = absint( $location_obj->term_id );
                    }
                }

                elseif( $geoloc ){

                    /**
                     *  Get Region ID
                     *  -------------
                     */
                    if( ! empty( $state_name ) ){

                        /**
                         *  Have State ID
                         *  -------------
                         */
                        $state_id    =      apply_filters( 'sdweddingdirectory/term-name/exists', [

                                                'term_name'   =>   esc_attr( $state_name )

                                            ] );
                        /**
                         *  Have State ID ?
                         *  ---------------
                         */
                        if( ! empty( $state_id ) ){

                            /**
                             *  Found Location ID
                             *  -----------------
                             */
                            $location_id        =      absint( $state_id );

                            /**
                             *  Make sure this term id is valid
                             *  -------------------------------
                             */
                            $region_id          =       apply_filters( 'sdweddingdirectory/term-name/exists', [

                                                            'term_name'       =>      esc_attr( $region_name ),

                                                            'parent_name'     =>      esc_attr( $state_name )

                                                        ] );

                            /**
                             *  Have Region ID ?
                             *  ----------------
                             */
                            if( ! empty( $region_id )  ){

                                /**
                                 *  Found Location ID
                                 *  -----------------
                                 */
                                $location_id        =      absint( $region_id );

                                /**
                                 *  Make sure this term id is valid
                                 *  -------------------------------
                                 */
                                $city_id            =       apply_filters( 'sdweddingdirectory/term-name/exists', [

                                                                'term_name'       =>      esc_attr( $city_name ),

                                                                'parent_name'     =>      esc_attr( $region_name )

                                                            ] );

                                /**
                                 *  Have City ID ?
                                 *  --------------
                                 */
                                if( ! empty( $city_id )  ){

                                    /**
                                     *  Found Location ID
                                     *  -----------------
                                     */
                                    $location_id        =      absint( $city_id );
                                }
                            }
                        }
                    }
                }

                /**
                 *  Normally we will work with ID :)
                 *  --------------------------------
                 */
                else{

                    /**
                     *  Get State ID
                     *  ------------
                     */
                    if( ! empty( $state_id ) ){

                        /**
                         *  Have State ID
                         *  -------------
                         */
                        $state_id    =      apply_filters( 'sdweddingdirectory/term-id/exists', [

                                                'term_id'   =>   absint( $state_id )

                                            ] );

                        /**
                         *  Have State ID ?
                         *  ---------------
                         */
                        if( ! empty( $state_id ) ){

                            /**
                             *  Get Location ID
                             *  ---------------
                             */
                            $location_id    =   absint( $state_id );

                            /**
                             *  Make sure this term id is valid
                             *  -------------------------------
                             */
                            $region_id      =   apply_filters( 'sdweddingdirectory/term-id/exists', [

                                                    'term_id'       =>      absint( $region_id ),

                                                    'parent_id'     =>      absint( $state_id )

                                                ] );

                            /**
                             *  Have Region ID ?
                             *  ----------------
                             */
                            if( ! empty( $region_id )  ){

                                /**
                                 *  Get Location ID
                                 *  ---------------
                                 */
                                $location_id    =       absint( $region_id );

                                /**
                                 *  Make sure this term id is valid
                                 *  -------------------------------
                                 */
                                $city_id        =       apply_filters( 'sdweddingdirectory/term-id/exists', [

                                                            'term_id'       =>      absint( $city_id ),

                                                            'parent_id'     =>      absint( $region_id )

                                                        ] );

                                /**
                                 *  Have City ID ?
                                 *  --------------
                                 */
                                if( ! empty( $city_id )  ){

                                    /**
                                     *  Get Location ID
                                     *  ---------------
                                     */
                                    $location_id    =       absint( $city_id );
                                }
                            }
                        }
                    }
                }

                /**
                 *  Categoy Is not Empty!
                 *  ---------------------
                 */
                if( ! empty( $location_id ) && empty( $value ) ){

                    /**
                     *  Category Object
                     *  ---------------
                     */
                    $location_obj    =   get_term( $location_id, $taxonomy );

                    /**
                     *  Make sure location object not empty!
                     *  ------------------------------------
                     */
                    if( ! empty( $location_obj ) ){

                        $value                          =       esc_attr( $location_obj->name );
                    }
                }

                /**
                 *  Return Input Category
                 *  ---------------------
                 */
                return  

                sprintf( '%1$s

                            <div    id="%19$s_location_section" 

                                    data-dropdown-item-id="%19$s_dropdown_item"

                                    data-input-id="%19$s_location_input" 

                                    data-dropdown-id="%19$s_location_dropdown" 

                                    data-dropdown-another-id="%19$s_category_dropdown" 

                                    data-term-id="%19$s_category_input"

                                    data-hidden-fields="%19$s_hidden_fields"

                                    class="sdweddingdirectory-dropdown-handler %2$s">

                                <div class="input-group">

                                    %15$s

                                    <input autocomplete="off" type="text" placeholder="%4$s"

                                        autocomplete="off" 

                                        id="%19$s_location_input"  name="%19$s_location_input" 

                                        data-post-type="%5$s"

                                        data-taxonomy="%18$s"

                                        data-display-id="%19$s_location_data"

                                        data-ajax="%12$s" data-enable-empty-term="%10$s"

                                        data-ajax-write-keyword="sdweddingdirectory_location_field_write_keyword_action"

                                        data-ajax-find-term="sdweddingdirectory_find_category_with_location_id"

                                        data-term-id="%19$s_category_input"

                                        data-last-value="%13$s" data-last-data="%7$s"

                                        data-dropdown-id="%19$s_location_dropdown" 

                                        data-value-id="%13$s"

                                        data-dropdown-js="%20$s"

                                        data-category-id="%21$s"

                                        data-depth-level="%22$s"

                                        data-display-tab="%23$s"

                                        class="form-control %16$s %18$s location-input" 

                                        value="%14$s">

                                    %17$s

                                </div>

                                <div class="sdweddingdirectory-input-search-dropdown" id="%19$s_location_dropdown">

                                    <div class="sdweddingdirectory-venue-location-data mt-2" id="%19$s_location_data">%8$s</div>

                                </div>

                            </div>

                        %9$s',

                        /**
                         *  1. Have Before Div ?
                         *  --------------------
                         */
                        $before_input,

                        /**
                         *  2. Have Target Class ?
                         *  ----------------------
                         */
                        '',

                        /**
                         *  3. Have Target ID ?
                         *  -------------------
                         */
                        ! empty( $target_id )   ?   sprintf( 'id="%1$s"', esc_attr( $target_id ) )  :   '',

                        /**
                         *  4. Translation Ready String
                         *  ---------------------------
                         */
                        esc_attr( $placeholder ),

                        /**
                         *  5. Post Type
                         *  ------------
                         */
                        esc_attr( $post_type ),

                        /**
                         *  6. Have Last Value ?
                         *  --------------------
                         */
                        '',

                        /**
                         *  7. Target Drop down
                         *  -------------------
                         */
                        apply_filters( 'sdweddingdirectory/term-id/json', [

                            'term_id'       =>      !   empty( $location_id )   ?   absint( $location_id )  :   absint( '0' )

                        ] ),

                        /**
                         *  8. Dropdown Data
                         *  ----------------
                         */
                        !   $dropdown_js

                        ?   apply_filters( 'sdweddingdirectory/find-location/category-id', [

                                'category_id'       =>      absint( $cat_id ),

                                'hide_empty'        =>      $hide_empty,

                                'post_type'         =>      $post_type,

                                'taxonomy'          =>      $taxonomy,

                                'allowed_terms'     =>      $allowed_terms,

                                'depth_level'       =>      $depth_level,

                                'display_tab'       =>      $display_tab

                            ] )

                        :   sprintf(    '<div class="card text-start">

                                            <div class="text-center mt-3">

                                                <p>%1$s <i class="fa fa-spinner fa-spin"></i></p>

                                            </div>

                                        </div>',

                                        /**
                                         *  1. Translation Ready String
                                         *  ---------------------------
                                         */
                                        esc_attr__( 'Wait a moment...', 'sdweddingdirectory' )
                            ),

                        /**
                         *  9. After Div ?
                         *  --------------
                         */
                        $after_input,

                        /**
                         *  10. Hide Emtpy Term
                         *  -------------------
                         */
                        absint( $hide_empty ),

                        /**
                         *  11. Have Dropdown ID
                         *  --------------------
                         */
                        ! empty( $dropdown_id )     ?   sprintf( 'id="%1$s"', esc_attr( $dropdown_id ) )    :   '',

                        /**
                         *  12. Load AJAX ?
                         *  ---------------
                         */
                        absint( $ajax_load ),

                        /**
                         *  13. Have Term ID ?
                         *  ------------------
                         */
                        ! empty( $location_id )     ?   absint(  $location_id  )    :   '',

                        /**
                         *  14. Value ?
                         *  -----------
                         */
                        esc_attr( $value ),

                        /**
                         *  15. Input Prefix
                         *  ----------------
                         */
                        $input_prefix,

                        /**
                         *  16. Form Style
                         *  --------------
                         */
                        sanitize_html_class( $input_style ),

                        /**
                         *  17. Input Suffix
                         *  ----------------
                         */
                        apply_filters( 'sdweddingdirectory/venue-location-input/input-suffix', '', [  

                            'parent_id'    =>   esc_attr( $parent_id ) 

                        ] ),

                        /**
                         *  18. Taxonoomy
                         *  -------------
                         */
                        esc_attr( $post_type . '-location' ),

                        /**
                         *  19. Random ID
                         *  -------------
                         */
                        esc_attr( $parent_id ),

                        /**
                         *  20. Enable Dropdown JS
                         *  ----------------------
                         */
                        absint( $dropdown_js ),

                        /**
                         *  21. Have Category ID
                         *  --------------------
                         */
                        absint( $cat_id ),

                        /**
                         *  22. Depth Level
                         *  ---------------
                         */
                        absint( $depth_level ),

                        /**
                         *  23. Nationwide ?
                         *  ----------------
                         */
                        esc_attr( $display_tab )
                );
            }
        }
    }

    /**
     *  ------------------------------
     *  SDWeddingDirectory - Dropdown - Object
     *  ------------------------------
     */
    SDWeddingDirectory_Location_Dropdown_Script::get_instance();
}
