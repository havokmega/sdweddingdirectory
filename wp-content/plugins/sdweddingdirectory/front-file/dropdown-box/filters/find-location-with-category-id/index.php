<?php
/**
 *  -------------------------------
 *  SDWeddingDirectory - Dropdown - Filters
 *  -------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Dropdown_Find_Location_With_Category_ID_Filters' ) && class_exists( 'SDWeddingDirectory_Dropdown_Filters' ) ){

    /**
     *  -------------------------------
     *  SDWeddingDirectory - Dropdown - Filters
     *  -------------------------------
     */
    class SDWeddingDirectory_Dropdown_Find_Location_With_Category_ID_Filters extends SDWeddingDirectory_Dropdown_Filters {

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
             *  Find Location Terms with Category ID connected
             *  ----------------------------------------------
             */
            add_filter( 'sdweddingdirectory/find-location/category-id', [ $this, 'find_location_term_with_category_id' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Allowed Tems
         *  ------------
         */
        public static function allowe_terms_filter( $args = [] ){

            /**
             *  Have Args
             *  ---------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Default Args
                 *  ------------
                 */
                extract( wp_parse_args( $args, [

                    'term_data'         =>      [],

                    'allowed_terms'     =>      [],

                    'handler'           =>      []

                ] ) );

                /**
                 *  Makes sure both data available
                 *  ------------------------------
                 */
                if( ! parent:: _is_array( $term_data ) && ! parent:: _is_array( $allowed_terms ) ){

                    return;
                }

                /**
                 *  Have Allowed Terms
                 *  ------------------
                 */
                if( parent:: _is_array( $allowed_terms ) ){

                    foreach( $allowed_terms as $index =>  $term_id ){

                        if( array_key_exists(  $term_id, $term_data ) ){

                            $handler[ absint( $term_id ) ]     =       apply_filters( 'sdweddingdirectory/term/name', [

                                                                            'term_id'       =>      $term_id,

                                                                            'taxonomy'      =>      esc_attr( 'venue-location' )

                                                                        ] );
                        }
                    }
                }

                /**
                 *  Category Allowed
                 *  ----------------
                 */
                if( parent:: _is_array( $handler ) ){

                    return          $handler;
                }
            }
        }

        /**
         *  Get Venue Location List
         *  -------------------------
         */
        public static function find_location_term_with_category_id( $args = [] ){

            /**
             *  Extract
             *  -------
             */
            extract( wp_parse_args( $args, [

                'category_id'           =>      absint( '0' ),

                'hide_empty'            =>      true,

                'rand_id'               =>      parent:: _rand(),

                'collection'            =>      '',

                'post_type'             =>      esc_attr( 'venue' ),

                'allowed_terms'         =>      [],

                'handler'               =>      [],

                'depth_level'           =>      absint( '3' )

            ] ) );

            /**
             *  Taxonomy
             *  --------
             */
            $taxonomy           =       esc_attr( $post_type . '-location' );

            /**
             *  Country Data
             *  ------------
             */
            $country            =       SDWeddingDirectory_Taxonomy:: get_taxonomy_parent( $taxonomy );

            /**
             *  Make sure allowed term not empty!
             *  ---------------------------------
             */
            if(  parent:: _is_array( $allowed_terms )  ){

                $country        =       self:: allowe_terms_filter( [

                                            'term_data'         =>      $country,

                                            'allowed_terms'     =>      $allowed_terms,

                                        ] );
            }

            /**
             *  Have Data ?
             *  -----------
             */
            if( parent:: _is_array( $country ) && $depth_level >= absint( '1' ) ){

                /**
                 *  State and City Data
                 *  -------------------
                 */
                foreach ( $country as $country_key => $country_name ) {

                    /**
                     *  Collection of Country
                     *  ---------------------
                     */
                    $handler[ $country_key ]   =   [];

                    /**
                     *  Have Data ?
                     *  -----------
                     */
                    if( ! empty( $country_key ) ){

                        /**
                         *  Have State
                         *  ----------
                         */
                        $_have_state    =   SDWeddingDirectory_Taxonomy:: get_taxonomy_child( $taxonomy, absint( $country_key ), $hide_empty );

                        /**
                         *  Make sure allowed term not empty!
                         *  ---------------------------------
                         */
                        if(  parent:: _is_array( $allowed_terms )  ){

                            $_have_state        =       self:: allowe_terms_filter( [

                                                            'term_data'         =>      $_have_state,

                                                            'allowed_terms'     =>      $allowed_terms,

                                                        ] );
                        }

                        /**
                         *  Have State
                         *  ----------
                         */
                        if( parent:: _is_array( $_have_state ) && $depth_level >= absint( '2' ) ){

                            /**
                             *  Get Each Data
                             *  -------------
                             */
                            foreach ( $_have_state  as $state_key => $state_name ) {

                                /**
                                 *  Allowed Empty Term
                                 *  ------------------
                                 */
                                if( ! $hide_empty ){

                                    $_found_post    =       true;
                                }

                                /**
                                 *  Checking
                                 *  --------
                                 */
                                else{

                                    $_found_post    =       parent:: _term_exists( [

                                                                'category_id'       =>      absint( $category_id ), 

                                                                'location_id'       =>      absint( $state_key ),

                                                                'post_type'         =>      $post_type

                                                            ] );
                                }

                                /**
                                 *  Found Post
                                 *  ----------
                                 */
                                if( $_found_post ){

                                    /**
                                     *  Collection of State
                                     *  -------------------
                                     */
                                    $handler[ $country_key ][ $state_key ]   =   [];

                                    /**
                                     *  Have Data ?
                                     *  -----------
                                     */
                                    if( ! empty( $state_key ) ){

                                        /**
                                         *  Have City Data ?
                                         *  ----------------
                                         */
                                        $_have_city     =   SDWeddingDirectory_Taxonomy:: get_taxonomy_child( $taxonomy, absint( $state_key ), $hide_empty );

                                        /**
                                         *  Make sure allowed term not empty!
                                         *  ---------------------------------
                                         */
                                        if(  parent:: _is_array( $allowed_terms )  ){

                                            $_have_city         =       self:: allowe_terms_filter( [

                                                                            'term_data'         =>      $_have_city,

                                                                            'allowed_terms'     =>      $allowed_terms,

                                                                        ] );
                                        }

                                        /**
                                         *  Make sure it's array
                                         *  --------------------
                                         */
                                        if( parent:: _is_array( $_have_city ) && $depth_level >= absint( '3' ) ){

                                            /**
                                             *  City Data 
                                             *  ---------
                                             */
                                            foreach ( $_have_city  as $city_id => $city_name ){


                                                /**
                                                 *  Allowed Empty Term
                                                 *  ------------------
                                                 */
                                                if( ! $hide_empty ){

                                                    $_found_post    =       true;
                                                }

                                                /**
                                                 *  Checking
                                                 *  --------
                                                 */
                                                else{

                                                    $_found_post    =   parent:: _term_exists( [

                                                                            'category_id'       =>      absint( $category_id ), 

                                                                            'location_id'       =>      absint( $city_id ),

                                                                            'post_type'         =>      $post_type

                                                                        ] );
                                                }

                                                if( $_found_post ){

                                                    /**
                                                     *  Collection of Region
                                                     *  --------------------
                                                     */
                                                    $handler[ $country_key ][ $state_key ][ $city_id ]   =   $city_id;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            /**
             *  Collection of Data
             *  ------------------
             */
            $have_location      =       [
                                            'country_list'      =>      array_filter( $handler ),

                                            'post_type'         =>      esc_attr( $post_type ),
                                        ];

            /**
             *  Return Location Data
             *  --------------------
             */
            return      parent:: _is_array( $have_location )

                        ?       self:: display_location_box( $have_location )

                        :       sprintf(   '<div class="card text-start">

                                                <div class="text-center mt-3">

                                                    <p>%1$s</p>

                                                </div>

                                            </div>',

                                            /**
                                             *  1. Translation Ready String
                                             *  ---------------------------
                                             */
                                            esc_attr__( 'No Location Found...', 'sdweddingdirectory' )
                                );
        }

        /**
         *  Location Dropdown Box
         *  ---------------------
         */
        public static function display_location_box( $args = [] ){

            /**
             *  Extract
             *  -------
             */
            extract( wp_parse_args( $args, [

                'handler'               =>      '',
            
                'rand_id'               =>      parent:: _rand(),

                'tab'                   =>      absint( '1' ),

                'tab_content'           =>      absint( '1' ),

                'post_type'             =>      esc_attr( 'venue' ),

                'country_list'          =>      [],

                'layout'                =>      absint( '2' ),

                'country_tab'           =>      apply_filters( 'sdweddingdirectory/find-location/country-tab/display', true ),

            ] ) );

            /**
             *  Taxonomy
             *  --------
             */
            $taxonomy       =       esc_attr( $post_type . '-location' );

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $country_list ) ){

                /**
                 *  Start
                 *  -----
                 */
                $handler     .=  '<div class="card text-start">';

                /**
                 *  Country Tab Enable ?
                 *  --------------------
                 */
                if( $country_tab ){

                    $handler     .=  '<ul class="nav nav-pills horizontal-tab-second location-dropdown" id="sdweddingdirectory-location-tax-tab" role="tablist">';

                    foreach( $country_list as $country_id => $state_list ){

                        /**
                         *  Location Object
                         *  ---------------
                         */
                        $country_obj        =       get_term( $country_id, $taxonomy );

                        $handler        .=

                        sprintf(   '<li class="nav-item">

                                        <a  class="nav-link %1$s" id="%2$s_%4$s-tab" 

                                            data-bs-toggle="tab" href="#%2$s_%4$s" role="tab" aria-controls="%2$s_%4$s" aria-selected="true">%3$s</a>

                                    </li>',

                                    /**
                                     *  1. Is active ?
                                     *  --------------
                                     */
                                    $tab == absint( '1' ) ? sanitize_html_class( 'active' ) : '',

                                    /**
                                     *  2. Key
                                     *  ------
                                     */
                                    sanitize_title( $country_obj->name ),

                                    /**
                                     *  3. Name
                                     *  -------
                                     */
                                    esc_attr( $country_obj->name ),

                                    /**
                                     *  4. Random ID 
                                     *  ------------
                                     */
                                    esc_attr( $rand_id )
                        );

                        $tab++;
                    }

                    $handler  .= '</ul>';
                }


                /**
                 *  Tab Content
                 *  -----------
                 */
                $handler  .= '<div class="tab-content" id="sdweddingdirectory-location-tax-tab-content">';

                foreach( $country_list as $country_id => $state_list ){

                    /**
                     *  Location Object
                     *  ---------------
                     */
                    $country_obj        =       get_term( $country_id, $taxonomy );

                    /**
                     *  Tab Content Start
                     *  -----------------
                     */
                    $handler     .=

                    sprintf( '<div class="tab-pane fade %3$s" id="%1$s_%4$s" role="tabpanel" aria-labelledby="%1$s_%4$s-tab">',

                            /**
                             *  1. Key
                             *  ------
                             */
                            sanitize_title( $country_obj->name ),

                            /**
                             *  2. Name
                             *  -------
                             */
                            esc_attr( $country_obj->name ),

                            /**
                             *  3. Is active ?
                             *  --------------
                             */
                            $tab_content == absint( '1' ) ? 'active show' : '',

                            /**
                             *  4. Random ID 
                             *  ------------
                             */
                            esc_attr( $rand_id )
                    );

                    $tab_content++;


                    /**
                     *  Have State Data ?
                     *  -----------------
                     */
                    if( parent:: _is_array( $state_list ) ){

                        /**
                         *  Location Layout
                         *  ---------------
                         */
                        $location_layout_args   =   [

                            'state_list'        =>      $state_list,

                            'taxonomy'          =>      esc_attr( $taxonomy )
                        ];

                        /**
                         *  Layou 1
                         *  -------
                         */
                        if( $layout == absint( '1' ) ){

                            $handler     .=     self:: location_style_one( $location_layout_args );
                        }

                        elseif( $layout == absint( '2' ) ){

                            $handler     .=     self:: location_style_two( $location_layout_args );
                        }
                    }


                    /**
                     *  Tab Content End
                     *  ---------------
                     */
                    $handler  .= '</div>';
                }

                $handler  .= '</div>';


                /**
                 *  End
                 *  ---
                 */
                $handler  .= '</div>';
            }

            /**
             *  Return Collection
             *  -----------------
             */
            return      $handler;
        }

        /**
         *  Location Style One
         *  ------------------
         */
        public static function location_style_one( $args = [] ){

            /**
             *  Extract Args
             *  ------------
             */
            extract( wp_parse_args( $args, [

                'handler'           =>      '',

                'state_list'        =>      [],

                'taxonomy'          =>      esc_attr( 'venue-location' )

            ] ) );

            /**
             *  Have State Data ?
             *  -----------------
             */
            if( parent:: _is_array( $state_list ) ){

                /**
                 *  State Item
                 *  ----------
                 */
                $handler  .=     '<ul class="state-item">';

                /**
                 *  State Loop
                 *  ----------
                 */
                foreach( $state_list as $state_id => $region_list ){

                    /**
                     *  Location Object
                     *  ---------------
                     */
                    $state_object        =       get_term( $state_id, $taxonomy );

                    /**
                     *  Collection of State List
                     *  ------------------------
                     */
                    $handler         .=         '<li>'  .   self:: _location_dropdown_item( [

                                                                'term_id'           =>      absint( $state_object->term_id ),

                                                            ] );

                    /**
                     *  Have Region Data ?
                     *  ------------------
                     */
                    if( parent:: _is_array( $region_list ) ){

                        /**
                         *  Before
                         *  ------
                         */
                        $handler     .=      '<ul>';

                        /**
                         *  Have Region Data ?
                         *  ------------------
                         */
                        foreach( $region_list as $region_id => $city_data  ){

                            /**
                             *  Location Object
                             *  ---------------
                             */
                            $region_object        =       get_term( $region_id, $taxonomy );

                            /**
                             *  Collection of State List
                             *  ------------------------
                             */
                            $handler            .=      sprintf( '<li>%1$s</li>', 

                                                            self:: _location_dropdown_item( [

                                                                'term_id'           =>      absint( $region_object->term_id ),

                                                            ] )
                                                        );
                        }

                        /**
                         *  Before
                         *  ------
                         */
                        $handler     .=      '</ul>';
                    }

                    /**
                     *  End li
                     *  ------
                     */
                    $handler         .=         '</li>';
                }

                /**
                 *  End State List
                 *  --------------
                 */
                $handler  .= '</ul>';
            }

            /**
             *  Return Collection
             *  -----------------
             */
            return      $handler;
        }

        /**
         *  Location Style Two
         *  ------------------
         */
        public static function location_style_two( $args = [] ){

            /**
             *  Extract Args
             *  ------------
             */
            extract( wp_parse_args( $args, [

                'handler'           =>      '',

                'state_list'        =>      [],

                'taxonomy'          =>      esc_attr( 'venue-location' )

            ] ) );

            /**
             *  Have State Data ?
             *  -----------------
             */
            if( parent:: _is_array( $state_list ) ){

                /**
                 *  Accordion Start
                 *  ---------------
                 */
                $handler  .=     '<div class="accordion">';

                /**
                 *  State Loop
                 *  ----------
                 */
                foreach( $state_list as $state_id => $region_list ){

                    /**
                     *  Section ID 
                     *  ----------
                     */
                    $section_id             =       esc_attr( parent:: _rand() );

                    /**
                     *  Have Child ?
                     *  ------------
                     */
                    $have_child             =       parent:: _is_array( $region_list );

                    /**
                     *  Location Object
                     *  ---------------
                     */
                    $state_object           =       get_term( $state_id, $taxonomy );

                    /**
                     *  Item Start
                     *  ----------
                     */
                    $handler         .=         '<div class="accordion-item">';

                    /**
                     *  Collection of State List
                     *  ------------------------
                     */
                    $handler         .=         sprintf(    '<button   class="%3$s bg-white collapsed" type="button" 

                                                            data-bs-toggle="collapse" data-bs-target="#%1$s" 

                                                            aria-expanded="false" aria-controls="%1$s">%2$s</button>',

                                                            /**
                                                             *  1. ID
                                                             *  -----
                                                             */
                                                            $section_id,

                                                            /**
                                                             *  2. Anchore
                                                             *  ----------
                                                             */
                                                            self:: _location_dropdown_item( [

                                                                'term_id'           =>      absint( $state_object->term_id ),

                                                                'a_class'           =>      $have_child

                                                                                            ?   'fw-normal text-dark'

                                                                                            :   'fw-normal text-dark search-item'

                                                            ] ),

                                                            /**
                                                             *  3. Have Child Term ?
                                                             *  --------------------
                                                             */
                                                            $have_child

                                                            ?   sanitize_html_class( 'accordion-button' )

                                                            :   parent:: _class( [ 'accordion-button', 'child-term-not-found'] )
                                                );

                    /**
                     *  Have Region Data ?
                     *  ------------------
                     */
                    if( $have_child ){

                        /**
                         *  Body Content Start
                         *  ------------------
                         */
                        $handler     .=     sprintf( '<div id="%1$s" class="accordion-collapse collapse list-group list-group-flush">',

                                                /**
                                                 *  1. ID
                                                 *  -----
                                                 */
                                                $section_id
                                            );

                        /**
                         *  Have Region Data ?
                         *  ------------------
                         */
                        foreach( $region_list as $region_id => $city_data  ){

                            /**
                             *  Location Object
                             *  ---------------
                             */
                            $region_object        =       get_term( $region_id, $taxonomy );

                            /**
                             *  Collection of State List
                             *  ------------------------
                             */
                            $handler            .=      self:: _location_dropdown_item( [

                                                            'term_id'           =>      absint( $region_object->term_id ),

                                                            'a_class'           =>      'list-group-item list-group-item-action search-item'

                                                        ] );
                        }

                        /**
                         *  Body Content End
                         *  ----------------
                         */
                        $handler  .=    '</div>';
                    }

                    /**
                     *  Item End
                     *  --------
                     */
                    $handler  .=    '</div>';
                }

                /**
                 *  Accordion End
                 *  -------------
                 */
                $handler  .=    '</div>';
            }

            /**
             *  Return Collection
             *  -----------------
             */
            return      $handler;
        }

        /**
         *  Location Dropdown Item
         *  ----------------------
         */
        public static function _location_dropdown_item( $args = [] ){

            /**
             *  Have Data ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'term_id'               =>      absint( '0' ),

                    'a_class'               =>      sanitize_html_class( 'search-item' )

                ] ) );

                /**
                 *  Have City Data ?
                 *  ----------------
                 */
                if( ! empty( $term_id ) ){

                    /**
                     *  Term Id to get Taxonomy
                     *  -----------------------
                     */
                    $taxonomy               =       parent:: get_taxonomy_by_term_id( $term_id );

                    /**
                     *  Term Object
                     *  -----------
                     */
                    $term_obj               =       get_term( $term_id, esc_attr( $taxonomy ) );

                    /**
                     *  Return Anchore
                     *  --------------
                     */
                    return      sprintf(   '<a  href="javascript:" 

                                                class="%3$s" 

                                                data-collection="%1$s" 

                                                data-placeholder-name="%2$s">%2$s</a>',

                                            /**
                                             *  1. Collection
                                             *  -------------
                                             */
                                            parent:: location_term_id_wise_collect_json( [

                                                'term_id'       =>      $term_id

                                            ] ),

                                            /**
                                             *  2. Full String
                                             *  --------------
                                             */
                                            esc_attr( $term_obj->name ),

                                            /**
                                             *  3. Anchor class
                                             *  ---------------
                                             */
                                            $a_class
                                );
                }
            }
        }
    }

    /**
     *  -------------------------------
     *  SDWeddingDirectory - Dropdown - Filters
     *  -------------------------------
     */
    SDWeddingDirectory_Dropdown_Find_Location_With_Category_ID_Filters::get_instance();
}