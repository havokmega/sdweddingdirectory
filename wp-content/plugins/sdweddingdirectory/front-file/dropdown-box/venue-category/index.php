<?php
/**
 *  ------------------------------
 *  SDWeddingDirectory - Dropdown - Object
 *  ------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Category_Dropdown_Script' ) && class_exists( 'SDWeddingDirectory_Dropdown_Script' ) ){

    /**
     *  ------------------------------
     *  SDWeddingDirectory - Dropdown - Object
     *  ------------------------------
     */
    class SDWeddingDirectory_Category_Dropdown_Script extends SDWeddingDirectory_Dropdown_Script{

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
            add_filter( 'sdweddingdirectory/input-category', [ $this, 'input_category_field' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Load Category Input Fields
         *  --------------------------
         */
        public static function input_category_field( $args = [] ){

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

                    'target_id'         =>      '',

                    'target_class'      =>      '',

                    'before_input'      =>      '',

                    'after_input'       =>      '',

                    'placeholder'       =>      esc_attr__( 'Search vendor category or name', 'sdweddingdirectory' ),

                    'value'             =>      '',

                    'cat_id'            =>      '',

                    'term_id'           =>      '',

                    'post_type'         =>      esc_attr( 'venue' ),

                    'taxonomy'          =>      esc_attr( 'venue-type' ),

                    'parent_id'         =>      esc_attr( parent:: _rand() ),

                    'allowed_terms'     =>      [],

                    'ajax_load'         =>      absint( '1' ),

                    'collection'        =>      [],

                    'cat_id'            =>      '',

                    'state_id'          =>      '',

                    'location'          =>      '',

                    'city_id'           =>      '',

                    'region_id'         =>      '',

                    'location_id'       =>      '',

                    'hide_empty'        =>      absint( '1' ),

                    'depth_level'       =>      absint( '1' )

                ] ) );

                /**
                 *  Categoy Is not Empty!
                 *  ---------------------
                 */
                if( ! empty( $cat_id ) && empty( $value ) ){

                    /**
                     *  Category Object
                     *  ---------------
                     */
                    $category_obj    =   get_term( $cat_id, esc_attr( $taxonomy ) );

                    /**
                     *  Make sure category object not empty!
                     *  ------------------------------------
                     */
                    if( ! empty( $category_obj ) ){

                        $value                          =      esc_attr( $category_obj->name );

                        $collection[ 'term_id' ]        =      esc_attr( $category_obj->term_id );

                        $collection[ 'term_name' ]      =      esc_attr( $value );

                        $collection[ 'cat_id' ]         =      esc_attr( $category_obj->term_id );
                    }
                }

                /**
                 *  Return Input Category
                 *  ---------------------
                 */
                return  

                sprintf( '%1$s

                            <div    id="%15$s_category_section" 

                                    data-input-id="%15$s_category_input" 

                                    data-dropdown-id="%15$s_category_dropdown" 

                                    data-term-id="%15$s_location_input"

                                    data-dropdown-another-id="%15$s_location_dropdown" 

                                    data-hidden-fields="%15$s_hidden_fields"

                                    class="sdweddingdirectory-dropdown-handler %2$s" %3$s>

                                <div class="input-group">

                                    <span class="d-flex align-items-center px-3 before-input-box"><i class="fa fa-search"></i></span>

                                    <input autocomplete="off" type="text" placeholder="%4$s"

                                        id="%15$s_category_input"  name="%15$s_category_input" 

                                        data-post-type="%5$s"

                                        data-taxonomy="%14$s"

                                        data-display-id="%15$s_category_data"

                                        data-term-id="%15$s_location_input"

                                        data-ajax="%16$s" data-enable-empty-term="%10$s"

                                        data-ajax-write-keyword="sdweddingdirectory_category_field_write_keyword_action"

                                        data-ajax-find-term="sdweddingdirectory_find_location_with_category_id"

                                        data-dropdown-id="%15$s_category_dropdown"

                                        class="form-control form-light %14$s category-input" 

                                        data-last-value="%12$s"  data-last-data="%7$s"

                                        data-value-id="%12$s"

                                        data-depth-level="%17$s"

                                        value="%13$s">
                                </div>

                                <div class="sdweddingdirectory-input-search-dropdown" id="%15$s_category_dropdown">

                                    <div class="sdweddingdirectory-venue-type-data mt-2" id="%15$s_category_data">%8$s</div>

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
                        $target_class,

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
                         *  6. Hide Empty Term
                         *  ------------------
                         */
                        $hide_empty,

                        /**
                         *  7. Have Cat ID Collection ?
                         *  ---------------------------
                         */
                        parent:: _is_array( $collection )

                        ?   esc_html( json_encode( $collection ) )

                        :   '',

                        /**
                         *  8. Dropdown Data
                         *  ----------------
                         */
                        apply_filters( 'sdweddingdirectory/find-category/location-id', [

                            'state_id'          =>      $state_id,

                            'location'          =>      $location,

                            'region_id'         =>      $region_id,

                            'city_id'           =>      $city_id,

                            'post_type'         =>      $post_type,

                            'taxonomy'          =>      $taxonomy,

                            'allowed_terms'     =>      $allowed_terms

                        ] ),

                        /**
                         *  9. After Div ?
                         *  --------------
                         */
                        $after_input,

                        /**
                         *  10. Hide Emtpy!
                         *  ---------------
                         */
                        absint( $hide_empty ),

                        /**
                         *  11. Have Dropdown ID
                         *  --------------------
                         */
                        '',

                        /**
                         *  12. Have Category ID ?
                         *  ----------------------
                         */
                        ! empty( $cat_id )     ?   absint( $cat_id )    :   '',

                        /**
                         *  13. Value ?
                         *  -----------
                         */
                        esc_attr( $value ),

                        /**
                         *  14. Taxonomy
                         *  ------------
                         */
                        esc_attr( $taxonomy ),

                        /**
                         *  15. Random ID
                         *  -------------
                         */
                        esc_attr( $parent_id ),

                        /**
                         *  16. Ajax Enable ?
                         *  -----------------
                         */
                        $ajax_load,

                        /**
                         *  17. Depth Level
                         *  ---------------
                         */
                        absint( $depth_level )
                );
            }
        }
    }

    /**
     *  ------------------------------
     *  SDWeddingDirectory - Dropdown - Object
     *  ------------------------------
     */
    SDWeddingDirectory_Category_Dropdown_Script::get_instance();
}
