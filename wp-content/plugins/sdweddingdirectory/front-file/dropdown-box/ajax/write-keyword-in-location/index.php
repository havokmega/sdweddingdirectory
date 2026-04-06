<?php
/**
 *  ------------------------------
 *  SDWeddingDirectory - Dropdown - Object
 *  ------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Dropdown_Location_Find_Name_Of_Location_AJAX' ) && class_exists( 'SDWeddingDirectory_Dropdown_AJAX' ) ){

    /**
     *  ------------------------------
     *  SDWeddingDirectory - Dropdown - Object
     *  ------------------------------
     */
    class SDWeddingDirectory_Dropdown_Location_Find_Name_Of_Location_AJAX extends SDWeddingDirectory_Dropdown_AJAX {

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
             *  1. Have AJAX action ?
             *  ---------------------
             */
            if(  wp_doing_ajax() ){

                /**
                 *  Have AJAX action ?
                 *  ------------------
                 */
                if( isset( $_POST['action'] ) && $_POST['action'] !== '' ){

                    /**
                     *  Have AJAX action ?
                     *  ------------------
                     */
                    $action     =   esc_attr( trim( $_POST['action'] ) );

                    /**
                     *  1.  Bit of security
                     *  -------------------
                     */
                    $allowed_actions = array(

                        /**
                         *  1. Get Venue Category Information
                         *  -----------------------------------
                         */
                        esc_attr( 'sdweddingdirectory_location_field_write_keyword_action' )
                    );

                    /**
                     *  If have action then load this object members
                     *  --------------------------------------------
                     */
                    if( in_array( $action, $allowed_actions) ) {

                        /**
                         *  Check the AJAX action with login user
                         *  -------------------------------------
                         */
                        if( is_user_logged_in() ){

                            /**
                             *  1. If user already login then AJAX action
                             *  -----------------------------------------
                             */
                            add_action( 'wp_ajax_' . $action, [ $this, $action ] );

                        }else{

                            /**
                             *  2. If user not login that mean is front end AJAX action
                             *  -------------------------------------------------------
                             */
                            add_action( 'wp_ajax_nopriv_' . $action, [ $this, $action ] );
                        }
                    }
                }
            }
        }

        /**
         *  Find Location
         *  -------------
         */
        public static function sdweddingdirectory_location_field_write_keyword_action(){

            /**
             *  Enable Search
             *  -------------
             */
            $have_keyword       =   isset( $_POST['input_data'] ) && $_POST['input_data'] != '' && ! empty( $_POST['input_data'] )

                                ?   true

                                :   false;

            /**
             *  Make sure input data not empty!
             *  -------------------------------
             */
            $category_id        =   isset( $_POST[ 'term_id' ] ) && $_POST[ 'term_id' ] != '' && ! empty( $_POST[ 'term_id' ] )

                                ?   true

                                :   false;

            /**
             *  Hide Empty!
             *  -----------
             */
            $hide_empty         =   $_POST[ 'hide_empty' ] == true || $_POST[ 'hide_empty' ] == absint( '1' )

                                ?   true

                                :   false;

            /**
             *  Find Location Name
             *  ------------------
             */
            if( $have_keyword ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( [

                    'collection'        =>      '',

                    'handler'           =>      ''

                ] );

                /**
                 *  Taxonomy
                 *  --------
                 */
                $taxonomy           =       esc_attr( $_POST[ 'post_type' ] . '-location' );

                /**
                 *  Get Country Data
                 *  ----------------
                 */
                $country            =       SDWeddingDirectory_Taxonomy:: get_taxonomy_parent( $taxonomy );

                /**
                 *  Have Country ?
                 *  --------------
                 */
                if( parent:: _is_array( $country ) ){

                    foreach( $country as $term_id => $tern_name ){

                        /**
                         *  Extract
                         *  -------
                         */
                        $collection   .=    self:: have_child_location( [

                                                'category_id'       =>      $category_id

                                                                            ?   absint( $_POST[ 'term_id' ] )

                                                                            :   '',

                                                'location_id'       =>      $term_id,

                                                'find_string'       =>      $_POST['input_data'],

                                                'post_type'         =>      esc_attr( $_POST[ 'post_type' ] ),

                                                'hide_empty'        =>      $hide_empty

                                            ] );
                    }
                }

                /**
                 *  Find Zip Code
                 *  -------------
                 */
                $collection   .=    apply_filters( 'sdweddingdirectory/find-location/pincode', [

                                        'category_id'       =>      absint( $_POST[ 'term_id' ] ),

                                        'input_data'        =>      esc_attr( $_POST['input_data'] ),

                                        'post_type'         =>      esc_attr( $_POST[ 'post_type' ] )

                                    ] );

                /**
                 *  Have Collection ?
                 *  -----------------
                 */
                if( !  empty( $collection ) ){

                    $handler    .=      sprintf(   '<div class="available-location-in-location">

                                                        <div class="card-header fw-bold py-3 user-select-none">%1$s</div>

                                                        <div class="list-of-location-list">

                                                            <ul class="list-group list-group-flush">%2$s</ul>

                                                        </div>

                                                    </div>',

                                                    /**
                                                     *  1. Found Location by Category
                                                     *  -----------------------------
                                                     */
                                                    esc_attr__( 'Found Locations', 'sdweddingdirectory' ),

                                                    /**
                                                     *  2. Get list
                                                     *  -----------
                                                     */
                                                    $collection
                                        );
                }

                /**
                 *  No Location Found
                 *  -----------------
                 */
                else{

                    $handler    .=      sprintf(   '<div class="available-location-in-location">

                                                        <div class="card-header fw-bold py-3 user-select-none">%1$s</div>

                                                    </div>',

                                                    /**
                                                     *  1. Found Location by Category
                                                     *  -----------------------------
                                                     */
                                                    esc_attr__( 'No Location Found!', 'sdweddingdirectory' )
                                        );
                }

                /**
                 *  Default Data Loaded
                 *  -------------------
                 */
                die( json_encode( [

                    'data'          =>      $handler

                ] ) );
            }

            /**
             *  Default Load
             *  ------------
             */
            else{

                /**
                 *  Default Data Loaded
                 *  -------------------
                 */
                die( json_encode( [

                    'data'          =>      apply_filters( 'sdweddingdirectory/find-location/category-id', [

                                                'hide_empty'        =>      $hide_empty,

                                                'post_type'         =>      isset( $_POST[ 'post_type' ] ) && ! empty( $_POST[ 'post_type' ] )

                                                                            ?   esc_attr( $_POST[ 'post_type' ] )

                                                                            :   esc_attr( 'venue' ),

                                                'category_id'       =>      isset( $_POST[ 'term_id' ] ) && ! empty( $_POST[ 'term_id' ] )

                                                                            ?   absint( $_POST[ 'term_id' ] )

                                                                            :   absint( '0' ),

                                                'depth_level'       =>      isset( $_POST[ 'depth_level' ] ) && ! empty( $_POST[ 'depth_level' ] )

                                                                            ?   absint( $_POST[ 'depth_level' ] )

                                                                            :   absint( '3' ),

                                                'display_tab'       =>      isset( $_POST[ 'display_tab' ] ) && ! empty( $_POST[ 'display_tab' ] )

                                                                            ?   esc_attr( $_POST[ 'display_tab' ] )

                                                                            :   esc_attr( 'all' ),
                                            ] )
                ] ) );
            }
        }

        /**
         *  Find Depth Level Location
         *  -------------------------
         */
        public static function have_child_location( $args = [] ){

            /**
             *  Extract
             *  -------
             */
            extract( wp_parse_args( $args, [

                'category_id'       =>      '',

                'location_id'       =>      '',

                'find_string'       =>      '',

                'handler'           =>      '',

                'post_type'         =>      esc_attr( 'venue' ),

                'hide_empty'        =>      true

            ] ) );

            /**
             *  Taxonomy
             *  --------
             */
            $taxonomy       =       esc_attr( $post_type . '-location' );

            /**
             *  Have City Data ?
             *  ----------------
             */
            $have_child     =       SDWeddingDirectory_Taxonomy:: get_taxonomy_child( $taxonomy, absint( $location_id ), $hide_empty );

            /**
             *  City Information
             *  ----------------
             */
            if( parent:: _is_array( $have_child ) ){

                /**
                 *  City Data 
                 *  ---------
                 */
                foreach ( $have_child  as $term_id => $term_name ){

                    /**
                     *  Handling
                     *  --------
                     */
                    $handler    .=      self:: add_location_in_list( [

                                            'category_id'       =>      $category_id,

                                            'location_id'       =>      $term_id,

                                            'find_string'       =>      $find_string,

                                            'post_type'         =>      $post_type,

                                            'hide_empty'        =>      $hide_empty

                                        ] );

                    /**
                     *  Handling
                     *  --------
                     */
                    $handler    .=      self:: have_child_location( [

                                            'category_id'       =>      $category_id,

                                            'location_id'       =>      $term_id,

                                            'find_string'       =>      $find_string,

                                            'post_type'         =>      $post_type,

                                            'hide_empty'        =>      $hide_empty

                                        ] );
                }
            }

            /**
             *  Return Data
             *  -----------
             */
            return      $handler;
        }

        /**
         *  Add location in list
         *  --------------------
         */
        public static function add_location_in_list( $args = [] ){

            /**
             *  Extract
             *  -------
             */
            extract( wp_parse_args( $args, [

                'category_id'       =>      '',

                'location_id'       =>      '',

                'find_string'       =>      '',

                'handler'           =>      '',

                'post_type'         =>      esc_attr( 'venue' ),

                'hide_empty'        =>      true

            ] ) );

            /**
             *  Taxonomy
             *  --------
             */
            $taxonomy       =       esc_attr( $post_type . '-location' );

            /**
             *  Location Object
             *  ---------------
             */
            $obj            =       get_term( $location_id, $taxonomy );

            /**
             *  Location Parent Object
             *  ----------------------
             */
            $parent_obj     =       get_term( $obj->parent, $taxonomy );


            /**
             *  Hide Empty!
             *  -----------
             */
            if( ! $hide_empty ){

                $_found_post    =       true;
            }

            else{

                $_found_post    =       parent:: _term_exists( [

                                            'category_id'       =>      absint( $category_id ),

                                            'location_id'       =>      absint( $location_id ),

                                            'post_type'         =>      esc_attr( $post_type )

                                        ] );
            }

            /**
             *  If category id + location id both available in venue post ?
             *  -------------------------------------------------------------
             */
            if(  $_found_post  ){

                /**
                 *  Have State in Word ?
                 *  --------------------
                 */
                if (  parent:: _have_word( $obj->name, $find_string )  ){

                    /**
                     *  Handler
                     *  -------
                     */
                    $handler    =

                    sprintf(   '<li class="list-group-item list-group-item-action">

                                    <a  class="search-item d-flex justify-content-between" 

                                        href="%1$s"

                                        data-collection="%2$s"

                                        data-placeholder-name="%3$s">

                                            <span>%4$s <small class="ms-1 text-muted">(%5$s)</small></span>

                                            <small class="text-muted">In %6$s</small>

                                    </a>

                                </li>',

                                /**
                                 *  1. Redirection Link
                                 *  -------------------
                                 */
                                esc_attr( 'javascript:' ),

                                /**
                                 *  2. Collection
                                 *  -------------
                                 */
                                parent:: location_term_id_wise_collect_json( [

                                    'term_id'       =>      $location_id,

                                    'taxonomy'      =>      sanitize_key( $taxonomy ),

                                ] ),

                                /**
                                 *  3. Full String
                                 *  --------------
                                 */
                                esc_attr( $obj->name ),

                                /**
                                 *  4. State String Find
                                 *  --------------------
                                 */
                                parent:: _string_highlight( esc_attr( $obj->name ), $find_string ),

                                /**
                                 *  5. Translation Ready String
                                 *  ---------------------------
                                 */
                                apply_filters( 'sdweddingdirectory/term-id/area-name', [

                                    'term_id'       =>      $location_id,

                                    'taxonomy'      =>      $taxonomy

                                ] ),

                                /**
                                 *  6. Country String Find
                                 *  ----------------------
                                 */
                                esc_attr( $parent_obj->name )
                    );
                }
            }

            /**
             *  Return
             *  ------
             */
            return      $handler;
        }
    }

    /**
     *  ------------------------------
     *  SDWeddingDirectory - Dropdown - Object
     *  ------------------------------
     */
    SDWeddingDirectory_Dropdown_Location_Find_Name_Of_Location_AJAX::get_instance();
}