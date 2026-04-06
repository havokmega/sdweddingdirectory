<?php
/**
 *  SDWeddingDirectory Search Venue
 *  -------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Search_Result' ) && class_exists( 'SDWeddingDirectory_Find_Post_Helper' ) ){

    /**
     *  SDWeddingDirectory Search Venue
     *  -------------------------
     */
    class SDWeddingDirectory_Search_Result extends SDWeddingDirectory_Find_Post_Helper{

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
         *  Get Query key and Value
         *  -----------------------
         */
        public static function _query_args(){

            /**
             *  Get Query Args
             *  --------------
             */
            return      [
                            'cat_id'            =>      self:: _cat_id(),

                            'region_id'         =>      self:: _region_id(),

                            'state_id'          =>      self:: _state_id(),

                            'location'          =>      self:: _location_slug(),

                            'city_id'           =>      self:: _city_id(),

                            'latitude'          =>      self:: _latitude(),

                            'longitude'         =>      self:: _longitude(),

                            'region_name'       =>      self:: _region_name(),

                            'city_name'         =>      self:: _city_name(),

                            'geoloc'            =>      self:: _geoloc(),

                            'pincode'           =>      self:: _pincode()
                        ];
        }

        /**
         *  Venue Search Page Context
         *  -------------------------
         */
        public static function is_venues_search_page(){

            if( is_page( 'venues' ) || is_page_template( 'page-venues.php' ) || is_tax( 'venue-type' ) ){

                return true;
            }

            return apply_filters( 'sdweddingdirectory/find-venue/is-venues-page', false );
        }

        /**
         *  Location Slug
         *  -------------
         */
        public static function _location_slug(){

            if( isset( $_GET['location'] ) && $_GET['location'] !== '' ){

                $location_slug = sanitize_title( wp_unslash( $_GET['location'] ) );

                $location_obj  = get_term_by( 'slug', $location_slug, esc_attr( 'venue-location' ) );

                if( ! empty( $location_obj ) && ! is_wp_error( $location_obj ) ){

                    return sanitize_title( $location_obj->slug );
                }
            }

            return '';
        }

        /**
         *  Have Category ID ?
         *  ==================
         */
        public static function _cat_id(){

            /**
             *  Have Cat ID ?
             *  ------------
             */
            if( isset( $_GET['cat_id'] ) && ! empty( $_GET['cat_id']  ) ){

                /**
                 *  Have Category ID ?
                 *  ------------------
                 */
                $cat_id     =   apply_filters( 'sdweddingdirectory/term-id/exists', [

                                    'term_id'       =>      absint( $_GET['cat_id'] ),

                                    'taxonomy'      =>      sanitize_key( 'venue-type' )

                                ] );

                /**
                 *  Make sure category id not empty!
                 *  --------------------------------
                 */
                if( ! empty( $cat_id ) ){

                    return      absint( $cat_id );
                }
            }
        }

        /**
         *  Have City Name ?
         *  ================
         */
        public static function _city_name(){

            /**
             *  Get Data
             *  --------
             */
            return      isset( $_GET[ 'city_name' ] ) && ! empty( $_GET[ 'city_name' ]  )

                        ?   esc_attr( $_GET[ 'city_name' ] )

                        :   '';
        }

        /**
         *  Have Region Name ?
         *  ==================
         */
        public static function _region_name(){

            /**
             *  Get Data
             *  --------
             */
            return      isset( $_GET[ 'region_name' ] ) && ! empty( $_GET[ 'region_name' ]  )

                        ?   esc_attr( $_GET[ 'region_name' ] )

                        :   '';
        }

        /**
         *  Have Geo Location Enable ?
         *  ==========================
         */
        public static function _geoloc(){

            /**
             *  Get Data
             *  --------
             */
            return      isset( $_GET[ 'geoloc' ] ) && ! empty( $_GET[ 'geoloc' ]  ) && $_GET[ 'geoloc' ] == absint( '1' )

                        ?   true

                        :   false;
        }

        /**
         *  Have PinCode ID ?
         *  =================
         */
        public static function _pincode(){

            /**
             *  Get Data
             *  --------
             */
            return      isset( $_GET[ 'pincode' ] ) && ! empty( $_GET[ 'pincode' ]  )

                        ?   esc_attr( $_GET[ 'pincode' ] )

                        :   '';
        }

        /**
         *  Have Latitude ID ?
         *  ==================
         */
        public static function _latitude(){

            /**
             *  Get Data
             *  --------
             */
            return      isset( $_GET[ 'latitude' ] ) && ! empty( $_GET[ 'latitude' ]  )

                        ?   esc_attr( $_GET[ 'latitude' ] )

                        :   '';
        }

        /**
         *  Have Longitude ID ?
         *  ===================
         */
        public static function _longitude(){

            /**
             *  Get Data
             *  --------
             */
            return      isset( $_GET[ 'longitude' ] ) && ! empty( $_GET[ 'longitude' ]  )

                        ?   esc_attr( $_GET[ 'longitude' ] )

                        :   '';
        }

        /**
         *  Have State ID ?
         *  ===============
         */
        public static function _state_id(){

            $location_slug = self:: _location_slug();

            if( ! empty( $location_slug ) ){

                $location_obj  = get_term_by( 'slug', $location_slug, esc_attr( 'venue-location' ) );

                if( ! empty( $location_obj ) && ! is_wp_error( $location_obj ) ){

                    return absint( $location_obj->term_id );
                }
            }

            /**
             *  Make sure GEO Enable in Query String ?
             *  --------------------------------------
             *  Then we make filter by term name foudn in datbase
             *  -------------------------------------------------
             */
            if( self:: _geoloc() ){

                /**
                 *  Get State ID
                 *  ------------
                 */
                if( isset( $_GET[ 'state_name' ] ) && ! empty( $_GET[ 'state_name' ]  ) ){

                    /**
                     *  Have State ID
                     *  -------------
                     */
                    $state_id    =       apply_filters( 'sdweddingdirectory/term-name/exists', [

                                            'term_name'   =>   esc_attr( $_GET[ 'state_name' ] )

                                        ] );

                    /**
                     *  State ID exists ?
                     *  -----------------
                     */
                    if( ! empty( $state_id ) ){

                        return      absint( $state_id );
                    }
                }
            }

            /**
             *  Found Query by location Ids
             *  ---------------------------
             */
            else{

                /**
                 *  Get State ID
                 *  ------------
                 */
                if( isset( $_GET[ 'state_id' ] ) && ! empty( $_GET[ 'state_id' ]  ) ){

                    /**
                     *  Have State ID
                     *  -------------
                     */
                    $state_id    =       apply_filters( 'sdweddingdirectory/term-id/exists', [

                                            'term_id'   =>   absint( $_GET[ 'state_id' ] )

                                        ] );

                    /**
                     *  State ID exists ?
                     *  -----------------
                     */
                    if( ! empty( $state_id ) ){

                        return      absint( $state_id );
                    }
                }
            }
        }

        /**
         *  Have Region ID ?
         *  ================
         */
        public static function _region_id(){

            /**
             *  Make sure GEO Enable in Query String ?
             *  --------------------------------------
             *  Then we make filter by term name foudn in datbase
             *  -------------------------------------------------
             */
            if( self:: _geoloc() ){

                /**
                 *  Region Condition
                 *  ----------------
                 */
                $_region_condition      =       isset( $_GET[ 'region_name' ] ) && ! empty( $_GET[ 'region_name' ]  );

                /**
                 *  State Condition
                 *  ---------------
                 */
                $_state_condition       =       isset( $_GET[ 'state_name' ] ) && ! empty( $_GET[ 'state_name' ]  );

                /**
                 *  Get Region ID
                 *  -------------
                 */
                if( $_region_condition && $_state_condition ){

                    /**
                     *  Have State ID
                     *  -------------
                     */
                    $state_id    =      apply_filters( 'sdweddingdirectory/term-name/exists', [

                                            'term_name'   =>   esc_attr( $_GET[ 'state_name' ] )

                                        ] );

                    /**
                     *  Have State ID ?
                     *  ---------------
                     */
                    if( ! empty( $state_id ) ){

                        /**
                         *  Make sure this term id is valid
                         *  -------------------------------
                         */
                        $region_id      =   apply_filters( 'sdweddingdirectory/term-name/exists', [

                                                'term_name'       =>      esc_attr( $_GET[ 'region_name' ] ),

                                                'parent_name'     =>      esc_attr( $_GET[ 'state_name' ] )

                                            ] );
                        /**
                         *  Have Region ID ?
                         *  ----------------
                         */
                        if( ! empty( $region_id )  ){

                            return      absint( $region_id );
                        }
                    }
                }
            }

            /**
             *  Found Query by location Ids
             *  ---------------------------
             */
            else{

                /**
                 *  Region Condition
                 *  ----------------
                 */
                $_region_condition      =       isset( $_GET[ 'region_id' ] ) && ! empty( $_GET[ 'region_id' ]  );

                /**
                 *  State Condition
                 *  ---------------
                 */
                $_state_condition       =       isset( $_GET[ 'state_id' ] ) && ! empty( $_GET[ 'state_id' ]  );

                /**
                 *  Get Region ID
                 *  -------------
                 */
                if( $_region_condition && $_state_condition ){

                    /**
                     *  Have State ID
                     *  -------------
                     */
                    $state_id    =      apply_filters( 'sdweddingdirectory/term-id/exists', [

                                            'term_id'   =>   absint( $_GET[ 'state_id' ] )

                                        ] );

                    /**
                     *  Have State ID ?
                     *  ---------------
                     */
                    if( ! empty( $state_id ) ){

                        /**
                         *  Make sure this term id is valid
                         *  -------------------------------
                         */
                        $region_id      =   apply_filters( 'sdweddingdirectory/term-id/exists', [

                                                'term_id'       =>      absint( $_GET[ 'region_id' ] ),

                                                'parent_id'     =>      absint( $_GET[ 'state_id' ] )

                                            ] );
                        /**
                         *  Have Region ID ?
                         *  ----------------
                         */
                        if( ! empty( $region_id )  ){

                            return      absint( $region_id );
                        }
                    }
                }
            }
        }

        /**
         *  Have City ID ?
         *  ==============
         */
        public static function _city_id(){

            /**
             *  Make sure GEO Enable in Query String ?
             *  --------------------------------------
             *  Then we make filter by term name foudn in datbase
             *  -------------------------------------------------
             */
            if( self:: _geoloc() ){

                /**
                 *  State Condition
                 *  ---------------
                 */
                $_state_condition       =       isset( $_GET[ 'state_name' ] ) && ! empty( $_GET[ 'state_name' ]  );

                /**
                 *  Region Condition
                 *  ----------------
                 */
                $_region_condition      =       isset( $_GET[ 'region_name' ] ) && ! empty( $_GET[ 'region_name' ]  );

                /**
                 *  City Condition
                 *  --------------
                 */
                $_city_condition       =        isset( $_GET[ 'city_name' ] ) && ! empty( $_GET[ 'city_name' ]  );

                /**
                 *  Get Region ID
                 *  -------------
                 */
                if( $_state_condition && $_region_condition && $_state_condition ){

                    /**
                     *  Have State ID
                     *  -------------
                     */
                    $state_id    =      apply_filters( 'sdweddingdirectory/term-name/exists', [

                                            'term_name'   =>   esc_attr( $_GET[ 'state_name' ] )

                                        ] );

                    /**
                     *  Have State ID ?
                     *  ---------------
                     */
                    if( ! empty( $state_id ) ){

                        /**
                         *  Make sure this term id is valid
                         *  -------------------------------
                         */
                        $region_id      =   apply_filters( 'sdweddingdirectory/term-name/exists', [

                                                'term_name'       =>      esc_attr( $_GET[ 'region_name' ] ),

                                                'parent_name'     =>      esc_attr( $_GET[ 'state_name' ] )

                                            ] );

                        /**
                         *  Have Region ID ?
                         *  ----------------
                         */
                        if( ! empty( $region_id )  ){

                            /**
                             *  Make sure this term id is valid
                             *  -------------------------------
                             */
                            $city_id      =     apply_filters( 'sdweddingdirectory/term-name/exists', [

                                                    'term_name'       =>      esc_attr( $_GET[ 'city_name' ] ),

                                                    'parent_name'     =>      esc_attr( $_GET[ 'region_name' ] )

                                                ] );

                            /**
                             *  Have City ID ?
                             *  --------------
                             */
                            if( ! empty( $city_id )  ){

                                return      absint( $city_id );
                            }
                        }
                    }
                }
            }

            /**
             *  Found Query by location Ids
             *  ---------------------------
             */
            else{

                /**
                 *  State Condition
                 *  ---------------
                 */
                $_state_condition       =       isset( $_GET[ 'state_id' ] ) && ! empty( $_GET[ 'state_id' ]  );

                /**
                 *  Region Condition
                 *  ----------------
                 */
                $_region_condition      =       isset( $_GET[ 'region_id' ] ) && ! empty( $_GET[ 'region_id' ]  );

                /**
                 *  City Condition
                 *  --------------
                 */
                $_city_condition       =       isset( $_GET[ 'city_id' ] ) && ! empty( $_GET[ 'city_id' ]  );

                /**
                 *  Get Region ID
                 *  -------------
                 */
                if( $_city_condition && $_region_condition && $_state_condition ){

                    /**
                     *  Have State ID
                     *  -------------
                     */
                    $state_id    =      apply_filters( 'sdweddingdirectory/term-id/exists', [

                                            'term_id'   =>   absint( $_GET[ 'state_id' ] )

                                        ] );

                    /**
                     *  Have State ID ?
                     *  ---------------
                     */
                    if( ! empty( $state_id ) ){

                        /**
                         *  Make sure this term id is valid
                         *  -------------------------------
                         */
                        $region_id      =   apply_filters( 'sdweddingdirectory/term-id/exists', [

                                                'term_id'       =>      absint( $_GET[ 'region_id' ] ),

                                                'parent_id'     =>      absint( $_GET[ 'state_id' ] )

                                            ] );

                        /**
                         *  Have Region ID ?
                         *  ----------------
                         */
                        if( ! empty( $region_id )  ){

                            /**
                             *  Make sure this term id is valid
                             *  -------------------------------
                             */
                            $city_id    =       apply_filters( 'sdweddingdirectory/term-id/exists', [

                                                    'term_id'       =>      absint( $_GET[ 'city_id' ] ),

                                                    'parent_id'     =>      absint( $_GET[ 'region_id' ] )

                                                ] );

                            /**
                             *  Have City ID ?
                             *  --------------
                             */
                            if( ! empty( $city_id )  ){

                                return      absint( $city_id );
                            }
                        }
                    }
                }
            }
        }

        /**
         *  Remove Filter Icon
         *  ------------------
         */
        public static function _remove_filter_icon(){

            return      '<span><i class="fa fa-close ms-1"></i></span>';
        }

        /**
         *  Remove Filter Class
         *  -------------------
         */
        public static function _remove_filter_class(){
            
            return      esc_attr( 'btn btn-outline-primary btn-sm btn-rounded me-1 mb-2' );
        }

        /**
         *  Have Query string data ?
         *  ------------------------
         */
        public static function _query_string( $string = '' ){

            /**
             *  Make sure string name not empty
             *  -------------------------------
             */
            if( empty( $string ) ){

                return;
            }

            /**
             *  Have Sub Category Filters ?
             *  ---------------------------
             */
            $_have_filter           =   isset( $_GET[ $string ] ) && $_GET[ $string ] != ''

                                    ?   true

                                    :   false;

            /**
             *  Have Sub Category ?
             *  -------------------
             */
            if( $_have_filter ){

                /**
                 *  Create Array of collection
                 *  --------------------------
                 */
                return      parent:: _coma_to_array( $_GET[ $string ] );

            }else{

                return      [];
            }
        }

        /**
         *  Have Filter Option Availalbe in Query Request ?
         *  -----------------------------------------------
         */
        public static function filter_option_available( $collection = [], $args = [] ){

            /**
             *  Extract Args
             *  ------------
             */
            extract( $args );

            /**
             *  Extract
             *  -------
             */
            extract( wp_parse_args( $collection, [

                'handler'               =>      [],

                'taxonomy'              =>      esc_attr( 'venue-type' ),

                'acf_group_box'         =>      apply_filters( 'sdweddingdirectory/dynamic-acf-group-box', [] )

            ] ) );

            /**
             *  Have Category
             *  -------------
             */
            if( ! empty( $cat_id ) ){

                /**
                 *  Make sure acf group box not empty!
                 *  ----------------------------------
                 */
                if( parent:: _is_array( $acf_group_box ) ){

                    /**
                     *  Loop
                     *  ----
                     */
                    foreach( $acf_group_box as $group_box_key => $group_box_data ){

                        /**
                         *  Group Box Data
                         *  --------------
                         */
                        extract( $group_box_data );

                        /**
                         *  Term Post ID
                         *  ------------
                         */
                        $term_post_id       =       esc_attr( $taxonomy ) .'_'. absint( $cat_id );

                        /**
                         *  Enable Section & Filter Widget
                         *  ------------------------------
                         */
                        $enable_section     =       sdwd_get_term_field(  sanitize_key( 'enable_' . $slug  ), $cat_id );

                        $enable_filter      =       sdwd_get_term_field(  sanitize_key( 'filter_widget_' . $slug  ), $cat_id );

                        /**
                         *  Make sure admin enable this section
                         *  -----------------------------------
                         */
                        if( $enable_section && $enable_filter ){

                            /**
                             *  Checkbox Data
                             *  -------------
                             */
                            $_have_options      =       apply_filters( 'sdweddingdirectory/term-box-group', [

                                                            'term_id'       =>      absint( $cat_id ),

                                                            'slug'          =>      esc_attr( $slug )

                                                        ] );
                            /**
                             *  Have Options
                             *  ------------
                             */
                            if( parent:: _is_array( $_have_options ) ){

                                $handler[ $slug ]   =       true;
                            }

                            else{

                                $handler[ $slug ]   =       false;
                            }
                        }
                    }
                }

                /**
                 *  Setting Filter
                 *  --------------
                 */
                $setting_available = sdwd_get_term_field( 'setting_available', $cat_id );

                $setting_options   = sdwd_get_term_repeater( 'setting_options', $cat_id );

                if( $setting_available && parent:: _is_array( $setting_options ) ){

                    $handler[ 'venue_setting' ] = true;
                }

                /**
                 *  Amenities Filter
                 *  ----------------
                 */
                $amenities_available = sdwd_get_term_field( 'amenities_available', $cat_id );

                $amenities_options   = sdwd_get_term_repeater( 'amenities_options', $cat_id );

                if( $amenities_available && parent:: _is_array( $amenities_options ) ){

                    $handler[ 'venue_amenities' ] = true;
                }
            }

            /**
             *  Make sure GEO Enable in Query String ?
             *  --------------------------------------
             *  Then we make filter by term name foudn in datbase
             *  -------------------------------------------------
             */
            if( $geoloc ){

                /**
                 *  Have State Name ?
                 *  -----------------
                 */
                if( ! empty( $state_name ) ){

                    $handler[ 'state_name' ]       =       true;
                }

                /**
                 *  Have Region Name ?
                 *  ------------------
                 */
                if( ! empty( $region_name ) ){

                    $handler[ 'region_name' ]       =       true;
                }

                /**
                 *  Have City Name ?
                 *  ----------------
                 */
                if( ! empty( $city_name ) ){

                    $handler[ 'city_name' ]       =       true;
                }
            }

            /**
             *  Found Query by location Ids
             *  ---------------------------
             */
            else{

                /**
                 *  Have State ID ?
                 *  ---------------
                 */
                if( ! empty( $state_id ) ){

                    $handler[ 'state_id' ]       =       true;
                }

                /**
                 *  Have Region ID ?
                 *  ----------------
                 */
                if( ! empty( $region_id ) ){

                    $handler[ 'region_id' ]       =       true;
                }

                /**
                 *  Have City ID ?
                 *  --------------
                 */
                if( ! empty( $city_id ) ){

                    $handler[ 'city_id' ]       =       true;
                }
            }

            /**
             *  Return Default Collection
             *  -------------------------
             */
            return      array_merge( $collection, $handler );
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  File
             *  ----
             */
            require_once        'helper/index.php';

            /**
             *  1. Load Script
             *  --------------
             */
            add_action( 'wp_enqueue_scripts', [ $this, 'sdweddingdirectory_script' ] );

            /**
             *  2. Load Search Venue
             *  ----------------------
             */
            add_action( 'sdweddingdirectory/find-venue', [ $this, 'sdweddingdirectory_find_venue_markup' ], absint( '10' ) );

            /**
             *  Sidebar Filter
             *  --------------
             */
            add_action( 'wp_footer', [ $this, 'sidebar_filter' ], absint( '10' ) );

            /**
             *  3. Enable Filter Button  ?
             *  --------------------------
             */
            add_filter( 'sdweddingdirectory/find-venue/enable-filter-button', [ $this, 'filter_option_available' ], absint( '10' ), absint( '2' ) );

            /**
             *  3. Have AJAX action ?
             *  ---------------------
             */
            if(  wp_doing_ajax()  ){

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
                         *  1. Find Venue
                         *  ---------------
                         */
                        esc_attr( 'sdweddingdirectory_load_venue_data' )
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
         *  1. Load Search Venue Page Script + Style
         *  ------------------------------------------
         */
        public static function sdweddingdirectory_script(){

            /**
             *   Is Search Venue Page Template Then Load this script + style
             *   -------------------------------------------------------------
             */
            if( is_page_template( 'user-template/search-venue.php' ) || self:: is_venues_search_page() ){

                /**
                 *  Bootstrap FrameWork JS File ( MIN FILE + UNMINFY FILE IS INCLUDED IN LIBRARY FOLDER )
                 *  -------------------------------------------------------------------------------------
                 */
                wp_enqueue_script(

                  /**
                   *  1. File Name
                   *  ------------
                   */
                  esc_attr( sanitize_title( __CLASS__ ) ),

                  /**
                   *  2. File Path
                   *  ------------
                   */
                  esc_url(   plugin_dir_url( __FILE__ ).'script.js'   ),

                  /**
                   *  3. Load After "JQUERY" Load
                   *  ---------------------------
                   */
                  array( 'jquery' ),

                  /**
                   *  4. Bootstrap - Library Version
                   *  ------------------------------
                   */
                  esc_attr( parent:: _file_version(     plugin_dir_path( __FILE__ ) .   'script.js'  ) ),

                  /**
                   *  5. Load in Footer
                   *  -----------------
                   */
                  true 
                );

                /**
                 *  Map Load Page Conditiom
                 *  -----------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/map', function( $args = [] ){

                    return  array_merge( $args, [ 'find_venue_page'   =>  true ] );

                } );

                /**
                 *  Marker Load Page Conditiom
                 *  --------------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/map-marker', function( $args = [] ){

                    return  array_merge( $args, [ 'find_venue_page'   =>  true ] );

                } );

                /**
                 *  Load Library Condition
                 *  -----------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/review', function( $args = [] ){

                    return  array_merge( $args, [ 'find_venue_page'   =>  true ] );
                    
                } );

                /**
                 *  Load Library Condition
                 *  -----------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/pagination', function( $args = [] ){

                    return  array_merge( $args, [ 'find_venue_page'   =>  true ] );
                    
                } );

                /**
                 *  Load Library Condition
                 *  -----------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/input-dropdown', function( $args = [] ){

                    return  array_merge( $args, [ 'find_venue_page'   =>  true ] );
                    
                } );

                /**
                 *  Load Library Condition
                 *  -----------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/date-picker', function( $args = [] ){

                    return  array_merge( $args, [ 'find_venue_page'   =>  true ] );
                    
                } );
            }
        }

        /**
         *  Sidebar Filter with Offcanvas
         *  -----------------------------
         */
        public static function sidebar_filter(){

            /**
             *  Make sure it's find venue page template
             *  -----------------------------------------
             */
            if( is_page_template( 'user-template/search-venue.php' ) ){

                ?>
                <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebar-filter" aria-labelledby="sidebar-filterLabel">

                    <div class="offcanvas-header">
                        
                        <h3 class="offcanvas-title" id="sidebar-filterLabel"><?php esc_attr_e( 'Filter', 'sdweddingdirectory' ); ?></h3>
                        
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>

                    </div>

                    <div class="offcanvas-body">
                    <?php

                        /**
                         *  Filter Option Here
                         *  ------------------
                         */
                        do_action( 'sdweddingdirectory/find-venue/filter-widget',

                            array_merge( self:: _query_args(), [

                                'layout'          =>    absint( '2' )

                            ] )
                        );

                    ?>
                    </div>

                    <div class="offcanvas-footer border-top">

                        <div class="offcanvas-body">
                    
                            <div class="col-12 d-grid text-center">
                            <?php

                                /**
                                 *  Hide Offcanvas
                                 *  --------------
                                 */
                                printf( '<a href="javascript:" data-bs-dismiss="offcanvas" class="btn btn-primary btn-lg fw-bold" id="result-counter">%1$s<span class="ms-2"></span></a>', 

                                    /**
                                     *  1. Translation Ready String
                                     *  ---------------------------
                                     */
                                    esc_attr__( 'View Result', 'sdweddingdirectory' )
                                );

                            ?>
                            </div>

                        </div>

                    </div>

                </div>
                <?php
            }
        }

        /**
         *  Should render venues landing page ?
         *  ----------------------------------
         */
        public static function should_render_venues_landing(){

            if( ! is_page( 'venues' ) && ! is_page_template( 'page-venues.php' ) ){

                return false;
            }

            if( empty( $_GET ) || ! is_array( $_GET ) ){

                return true;
            }

            $query_args = wp_unslash( $_GET );

            if( ! is_array( $query_args ) ){

                return true;
            }

            $query_args = array_filter( $query_args, function( $value ){

                if( is_array( $value ) ){

                    return count( array_filter( $value, function( $item ){
                        return $item !== '' && $item !== null;
                    } ) ) > absint( '0' );
                }

                return $value !== '' && $value !== null;
            } );

            unset( $query_args['page_id'] );

            return empty( $query_args );
        }

        /**
         *  Venues Landing Page Markup
         *  --------------------------
         */
        public static function venues_landing_page_markup(){

            $location_placeholder_base = defined( 'SDWEDDINGDIRECTORY_SHORTCODE_URL' )
                ? trailingslashit( SDWEDDINGDIRECTORY_SHORTCODE_URL )
                : esc_url_raw( home_url( '/wp-content/plugins/sdweddingdirectory/shortcodes/' ) );

            $location_placeholder = esc_url( $location_placeholder_base . 'shortcodes/venue-location/images/venue-location.jpg' );

            $get_theme_location_image = function( $location_slug = '' ){

                $location_slug = sanitize_title( (string) $location_slug );

                if( empty( $location_slug ) || ! function_exists( 'get_theme_file_uri' ) || ! function_exists( 'get_theme_file_path' ) ){
                    return '';
                }

                $relative_path = 'assets/images/locations/' . $location_slug . '.jpg';
                $absolute_path = get_theme_file_path( $relative_path );

                if( ! empty( $absolute_path ) && file_exists( $absolute_path ) ){
                    return esc_url( get_theme_file_uri( $relative_path ) );
                }

                return '';
            };

            $city_breakpoints = wp_json_encode( [
                '0'    => [ 'items' => 2 ],
                '576'  => [ 'items' => 3 ],
                '768'  => [ 'items' => 4 ],
                '992'  => [ 'items' => 5 ],
                '1200' => [ 'items' => 7 ],
            ] );

            $homepage_city_slugs = [
                'carlsbad',
                'chula-vista',
                'coronado',
                'del-mar',
                'el-cajon',
                'encinitas',
                'escondido',
                'imperial-beach',
                'julian',
                'la-jolla',
                'la-mesa',
                'oceanside',
                'poway',
                'rancho-santa-fe',
                'san-diego',
                'san-marcos',
                'solana-beach',
            ];

            $homepage_city_terms = [];

            foreach( $homepage_city_slugs as $city_slug ){

                $city_term = get_term_by( 'slug', sanitize_title( $city_slug ), esc_attr( 'venue-location' ) );

                if( ! empty( $city_term ) && ! is_wp_error( $city_term ) ){

                    $homepage_city_terms[] = $city_term;
                }
            }

            if( ! parent:: _is_array( $homepage_city_terms ) ){

                $fallback_terms = get_terms( [
                    'taxonomy'       =>  esc_attr( 'venue-location' ),
                    'hide_empty'     =>  false,
                    'orderby'        =>  esc_attr( 'name' ),
                    'order'          =>  esc_attr( 'ASC' ),
                    'parent'         =>  0,
                ] );

                if( is_array( $fallback_terms ) ){

                    $homepage_city_terms = array_slice( $fallback_terms, 0, absint( '17' ) );
                }else{

                    $homepage_city_terms = [];
                }
            }

            $all_city_terms = get_terms( [
                'taxonomy'       =>  esc_attr( 'venue-location' ),
                'hide_empty'     =>  false,
                'orderby'        =>  esc_attr( 'name' ),
                'order'          =>  esc_attr( 'ASC' ),
            ] );

            $all_city_terms = is_array( $all_city_terms ) ? $all_city_terms : [];

            $top_city_slugs = [ 'san-diego', 'la-jolla', 'coronado', 'del-mar', 'carlsbad' ];

            $top_city_terms = [];

            foreach( $top_city_slugs as $top_city_slug ){

                $top_city_term = get_term_by( 'slug', sanitize_title( $top_city_slug ), esc_attr( 'venue-location' ) );

                if( ! empty( $top_city_term ) && ! is_wp_error( $top_city_term ) ){

                    $top_city_terms[] = $top_city_term;
                }
            }

            $venue_category_terms = get_terms( [
                'taxonomy'       =>  esc_attr( 'venue-type' ),
                'hide_empty'     =>  false,
                'orderby'        =>  esc_attr( 'name' ),
                'order'          =>  esc_attr( 'ASC' ),
                'parent'         =>  absint( '0' ),
            ] );

            $venue_category_terms = is_array( $venue_category_terms ) ? $venue_category_terms : [];

            $vendor_category_terms = get_terms( [
                'taxonomy'       =>  esc_attr( 'vendor-category' ),
                'hide_empty'     =>  false,
                'orderby'        =>  esc_attr( 'name' ),
                'order'          =>  esc_attr( 'ASC' ),
                'parent'         =>  absint( '0' ),
            ] );

            $vendor_category_terms = is_array( $vendor_category_terms ) ? $vendor_category_terms : [];
            ?>
            <div class="container py-5 sd-venues-landing-page">

                <section class="sd-venues-landing-section mb-5">
                    <h2 class="mb-4"><?php echo esc_html__( 'Venues by Area', 'sdweddingdirectory' ); ?></h2>
                    <div class="owl-carousel sdweddingdirectory-owl-carousel sd-venues-area-carousel"
                        data-breakpoint="<?php echo esc_attr( $city_breakpoints ); ?>"
                        data-dots="false"
                        data-nav="true"
                        data-loop="true"
                        data-margin="12"
                        data-auto-play="false"
                        data-auto-play-speed="1000"
                        data-auto-play-timeout="5000">
                        <?php foreach( $homepage_city_terms as $city_term ){

                            $city_link = add_query_arg(
                                [ 'location' => sanitize_title( $city_term->slug ) ],
                                home_url( '/venues/' )
                            );

                            $city_image = apply_filters( 'sdweddingdirectory/term/image', [
                                'term_id'       =>  absint( $city_term->term_id ),
                                'taxonomy'      =>  esc_attr( 'venue-location' ),
                                'image_size'    =>  esc_attr( 'sdweddingdirectory_img_550x460' ),
                                'default_img'   =>  $location_placeholder,
                            ] );

                            $theme_city_image = $get_theme_location_image( $city_term->slug );

                            if( ! empty( $theme_city_image ) ){
                                $city_image = $theme_city_image;
                            }
                            ?>
                            <div class="item">
                                <a class="sd-venues-area-slide" href="<?php echo esc_url( $city_link ); ?>">
                                    <span class="sd-venues-area-image">
                                        <img src="<?php echo esc_url( $city_image ); ?>" alt="<?php echo esc_attr( $city_term->name ); ?>">
                                    </span>
                                    <span class="sd-venues-area-name"><?php echo esc_html( $city_term->name ); ?></span>
                                    <span class="sd-venues-area-count">
                                        <?php
                                        printf(
                                            esc_html__( '%1$s venues', 'sdweddingdirectory' ),
                                            esc_html( number_format_i18n( absint( $city_term->count ) ) )
                                        );
                                        ?>
                                    </span>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                </section>

                <section class="sd-venues-landing-popout mb-5">
                    <div class="sd-venues-landing-popout-frame">
                        <div class="sd-venues-landing-popout-card">
                            <h3><?php echo esc_html__( 'Find Your San Diego Wedding Venue', 'sdweddingdirectory' ); ?></h3>
                            <p class="mb-0"><?php echo esc_html__( 'Search through different venue types to find the place that matches your vibe just right.', 'sdweddingdirectory' ); ?></p>
                        </div>
                        <div class="sd-venues-landing-popout-image">
                            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/venues-pop-out.png' ); ?>" alt="<?php echo esc_attr__( 'Find Your San Diego Wedding Venue', 'sdweddingdirectory' ); ?>">
                        </div>
                    </div>
                </section>

                <?php foreach( $top_city_terms as $top_city_term ){

                    $top_city_link = add_query_arg(
                        [ 'location' => sanitize_title( $top_city_term->slug ) ],
                        home_url( '/venues/' )
                    );

                    $city_venue_query = new WP_Query( [
                        'post_type'         =>  esc_attr( 'venue' ),
                        'post_status'       =>  esc_attr( 'publish' ),
                        'posts_per_page'    =>  absint( '4' ),
                        'tax_query'         =>  [
                            [
                                'taxonomy'  =>  esc_attr( 'venue-location' ),
                                'field'     =>  esc_attr( 'term_id' ),
                                'terms'     =>  [ absint( $top_city_term->term_id ) ],
                            ]
                        ],
                        'orderby'           =>  esc_attr( 'date' ),
                        'order'             =>  esc_attr( 'DESC' ),
                    ] );
                    ?>
                    <section class="sd-venues-city-section mb-5">
                        <h2 class="mb-4">
                            <?php
                            printf(
                                esc_html__( 'Venues in %1$s', 'sdweddingdirectory' ),
                                esc_html( $top_city_term->name )
                            );
                            ?>
                        </h2>
                        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-4">
                            <?php
                            if( $city_venue_query->have_posts() ){

                                while( $city_venue_query->have_posts() ){ $city_venue_query->the_post();

                                    $venue_id = absint( get_the_ID() );
                                    $venue_link = get_the_permalink( $venue_id );

                                    $venue_image = apply_filters( 'sdweddingdirectory/venue/post/thumbnail', [
                                        'post_id'   =>  $venue_id,
                                    ] );

                                    $venue_rating = apply_filters( 'sdweddingdirectory/rating/average', '', [
                                        'venue_id'    =>  $venue_id,
                                        'before'        =>  '<span>',
                                        'after'         =>  '</span>',
                                        'icon'          =>  '<i class="fa fa-star me-1"></i>',
                                    ] );

                                    $venue_price = class_exists( 'SDWeddingDirectory_Venue' )
                                                    ? SDWeddingDirectory_Venue:: venue_post_price_text( $venue_id )
                                                    : '';

                                    $venue_capacity = class_exists( 'SDWeddingDirectory_Venue' )
                                                        ? SDWeddingDirectory_Venue:: venue_post_capacity_text( $venue_id )
                                                        : '';
                                    ?>
                                    <div class="col">
                                        <article class="sd-venues-city-card h-100">
                                            <a class="sd-venues-city-card-image" href="<?php echo esc_url( $venue_link ); ?>">
                                                <img src="<?php echo esc_url( $venue_image ); ?>" alt="<?php echo esc_attr( get_the_title( $venue_id ) ); ?>">
                                            </a>
                                            <div class="sd-venues-city-card-body">
                                                <h4><a href="<?php echo esc_url( $venue_link ); ?>"><?php echo esc_html( get_the_title( $venue_id ) ); ?></a></h4>
                                                <p class="sd-venues-city-card-rating mb-2">
                                                    <?php
                                                    if( ! empty( $venue_rating ) ){
                                                        echo wp_kses_post( $venue_rating );
                                                    }else{
                                                        echo wp_kses_post( '<span><i class="fa fa-star me-1"></i>' . esc_html__( 'New venue', 'sdweddingdirectory' ) . '</span>' );
                                                    }
                                                    ?>
                                                </p>
                                                <?php if( ! empty( $venue_price ) ){ ?>
                                                    <p class="sd-venues-city-card-price mb-2"><?php echo esc_html( $venue_price ); ?></p>
                                                <?php } ?>
                                                <?php if( ! empty( $venue_capacity ) ){ ?>
                                                    <p class="sd-venues-city-card-capacity mb-2">
                                                        <i class="fa fa-users me-1"></i><?php echo esc_html( $venue_capacity ); ?>
                                                    </p>
                                                <?php } ?>
                                                <a class="sd-venues-city-card-link" href="<?php echo esc_url( $venue_link ); ?>">
                                                    <?php echo esc_html__( 'Request Pricing', 'sdweddingdirectory' ); ?>
                                                </a>
                                            </div>
                                        </article>
                                    </div>
                                <?php }

                                wp_reset_postdata();
                            }
                            ?>
                        </div>
                        <div class="mt-3">
                            <a class="sd-venues-city-see-all" href="<?php echo esc_url( $top_city_link ); ?>">
                                <?php
                                printf(
                                    esc_html__( 'See all %1$s venues', 'sdweddingdirectory' ),
                                    esc_html( $top_city_term->name )
                                );
                                ?>
                            </a>
                        </div>
                    </section>
                <?php } ?>

                <section class="sd-venues-seo-three-column mb-5">
                    <h2 class="mb-2"><?php echo esc_html__( 'San Diego Wedding Venues', 'sdweddingdirectory' ); ?></h2>
                    <p class="text-muted mb-4"><?php echo esc_html__( 'Beachfront views, garden settings, modern ballrooms, and historic estates. Whatever your style or budget, San Diego has a venue that fits.', 'sdweddingdirectory' ); ?></p>
                    <div class="row g-4">
                        <div class="col-12 col-lg-4">
                            <h4><?php echo esc_html__( 'Picking a Wedding Venue', 'sdweddingdirectory' ); ?></h4>
                            <p class="mb-0"><?php echo esc_html__( 'Start with your guest count, budget, and overall vision. San Diego offers everything from intimate cliff-top ceremonies to large-scale ballroom receptions. Narrowing down what matters most — location, capacity, or vibe — will help you find the right match faster.', 'sdweddingdirectory' ); ?></p>
                        </div>
                        <div class="col-12 col-lg-4">
                            <h4><?php echo esc_html__( 'Outdoor Wedding Venue Spaces', 'sdweddingdirectory' ); ?></h4>
                            <p class="mb-0"><?php echo esc_html__( 'San Diego\'s year-round sunshine makes it one of the best cities in the country for outdoor weddings. From ocean-view terraces and vineyard estates to botanical gardens and ranch-style properties, there\'s no shortage of beautiful open-air settings.', 'sdweddingdirectory' ); ?></p>
                        </div>
                        <div class="col-12 col-lg-4">
                            <h4><?php echo esc_html__( 'Indoor Wedding Spaces', 'sdweddingdirectory' ); ?></h4>
                            <p class="mb-0"><?php echo esc_html__( 'For couples who want a polished, weather-proof setting, San Diego has a wide range of indoor venues. Think historic ballrooms, boutique hotels, modern lofts, and elegant event spaces — many with the flexibility to host both your ceremony and reception under one roof.', 'sdweddingdirectory' ); ?></p>
                        </div>
                    </div>
                </section>

                <section class="sd-venues-button-row mb-5">
                    <?php
                    if( parent:: _is_array( $venue_category_terms ) ){
                        ?>
                        <div class="sd-venues-button-wrap d-flex flex-wrap justify-content-start gap-2">
                            <?php foreach( $venue_category_terms as $venue_category_term ){

                                $venue_category_link = get_term_link( $venue_category_term );

                                if( is_wp_error( $venue_category_link ) ){
                                    continue;
                                }
                                ?>
                                <a class="btn btn-outline-dark btn-sm rounded-pill" style="font-weight: 600;" href="<?php echo esc_url( $venue_category_link ); ?>">
                                    <?php echo esc_html( $venue_category_term->name ); ?>
                                </a>
                            <?php } ?>
                        </div>
                        <?php
                    }
                    ?>
                </section>

                <section class="sd-venues-city-links mb-5">
                    <h3 class="mb-3"><?php echo esc_html__( 'San Diego Wedding Venues', 'sdweddingdirectory' ); ?></h3>
                    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-2">
                        <?php foreach( $all_city_terms as $city_term ){
                            $city_link = add_query_arg(
                                [ 'location' => sanitize_title( $city_term->slug ) ],
                                home_url( '/venues/' )
                            );
                            ?>
                            <div class="col">
                                <div class="sd-home-city-link">
                                    <a href="<?php echo esc_url( $city_link ); ?>"><?php echo esc_html( $city_term->name ); ?> <?php esc_html_e( 'Wedding Venues', 'sdweddingdirectory' ); ?></a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </section>

                <section class="sd-venues-vendor-inline-links">
                    <h3 class="mb-3"><?php echo esc_html__( 'San Diego Wedding Vendors', 'sdweddingdirectory' ); ?></h3>
                    <?php
                    $vendor_rows = [
                        [
                            [ 'label' => 'Bands', 'path' => '/vendors/bands/' ],
                            [ 'label' => 'Beauty', 'path' => '/vendors/beauty/' ],
                            [ 'label' => 'Bridal Salons', 'path' => '/vendors/bridal-salons/' ],
                            [ 'label' => 'Catering', 'path' => '/vendors/catering/' ],
                            [ 'label' => 'DJs', 'path' => '/vendors/djs/' ],
                            [ 'label' => 'Florists', 'path' => '/vendors/florists/' ],
                            [ 'label' => 'Officiants', 'path' => '/vendors/officiants/' ],
                        ],
                        [
                            [ 'label' => 'Photo Booths', 'path' => '/vendors/photo-booths/' ],
                            [ 'label' => 'Photographers', 'path' => '/vendors/photographers/' ],
                            [ 'label' => 'Planners', 'path' => '/vendors/planners/' ],
                            [ 'label' => 'Rentals', 'path' => '/vendors/rentals/' ],
                            [ 'label' => 'Stationers', 'path' => '/vendors/stationers/' ],
                            [ 'label' => 'Transportation', 'path' => '/vendors/transportation/' ],
                            [ 'label' => 'Videographers', 'path' => '/vendors/videographers/' ],
                        ],
                        [
                            [ 'label' => 'Wedding Cakes', 'path' => '/vendors/wedding-cakes/' ],
                            [ 'label' => 'Wedding Decor', 'path' => '/vendors/wedding-decor/' ],
                            [ 'label' => 'Wedding Favors', 'path' => '/vendors/wedding-favors/' ],
                            [ 'label' => 'Wedding Invitations', 'path' => '/vendors/wedding-invitations/' ],
                            [ 'label' => 'Wedding Jewelers', 'path' => '/vendors/wedding-jewelers/' ],
                            [ 'label' => 'Wedding Planners', 'path' => '/vendors/wedding-planners/' ],
                        ],
                    ];
                    ?>
                    <div class="sd-home-link-group">
                        <?php foreach( $vendor_rows as $row ) : ?>
                            <div class="sd-home-link-row">
                                <?php foreach( $row as $idx => $item ) : ?>
                                    <a href="<?php echo esc_url( home_url( $item['path'] ) ); ?>"><?php echo esc_html( $item['label'] ); ?></a><?php if( $idx < count( $row ) - 1 ) : ?><span class="sd-home-link-sep">&bull;</span><?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            </div>
            <?php
        }

        /**
         *  2. Load Venue With Parameter
         *  ------------------------------
         */
        public static function sdweddingdirectory_find_venue_markup(){

            /**
             *  Load Header
             *  -----------
             */
            get_header();

            /**
             *  Page Args
             *  ---------
             */
            $page_args      =   [
                                    'query_args'    =>      self:: _query_args(),

                                    'parent_id'     =>      esc_attr( parent:: _rand() ),

                                    'have_map'      =>      parent:: sdweddingdirectory_have_map(),

                                    'result_page'   =>      apply_filters( 'sdweddingdirectory/find-venue-page', [] ),
                                ];
            /**
             *  Get Data
             *  --------
             */
            extract( $page_args );

            /**
             *  Venues Page Layout
             *  ------------------
             */
            if( self:: is_venues_search_page() ){
                ?>
                <?php do_action( 'sdweddingdirectory/page-header-banner' ); ?>

                <?php
                if( self:: should_render_venues_landing() ){

                    self:: venues_landing_page_markup();

                    get_footer();

                    return;
                }
                ?>

                <div class="container py-5 sdweddingdirectory-venues-search-page">

                    <form class="sdweddingdirectory-result-page" method="get" action="<?php echo esc_url( home_url( '/venues/' ) ); ?>">

                        <div class="row g-4" id="sdweddingdirectory_find_venue_section">

                            <div class="col-12 col-lg-3">

                                <div class="sdweddingdirectory-venues-sidebar">

                                    <div class="row">
                                    <?php

                                        $widget_args = array_merge( $query_args, [

                                            'layout'    =>  absint( '2' )
                                        ] );

                                        if( class_exists( 'SDWeddingDirectory_Search_Result_Filter_Widget_Active_Filters' ) ){

                                            SDWeddingDirectory_Search_Result_Filter_Widget_Active_Filters:: widget( $widget_args );
                                        }

                                        if( class_exists( 'SDWeddingDirectory_Search_Result_Filter_Widget_Sub_Category' ) ){

                                            SDWeddingDirectory_Search_Result_Filter_Widget_Sub_Category:: widget( $widget_args );
                                        }

                                        if( class_exists( 'SDWeddingDirectory_Search_Result_Filter_Widget_Setting' ) ){

                                            SDWeddingDirectory_Search_Result_Filter_Widget_Setting:: widget( $widget_args );
                                        }

                                        if( class_exists( 'SDWeddingDirectory_Search_Result_Filter_Widget_Amenities' ) ){

                                            SDWeddingDirectory_Search_Result_Filter_Widget_Amenities:: widget( $widget_args );
                                        }

                                        if( class_exists( 'SDWeddingDirectory_Search_Result_Filter_Widget_Seating_Capacity' ) ){

                                            SDWeddingDirectory_Search_Result_Filter_Widget_Seating_Capacity:: widget( $widget_args );
                                        }

                                        if( class_exists( 'SDWeddingDirectory_Search_Result_Filter_Widget_Pricing' ) ){

                                            SDWeddingDirectory_Search_Result_Filter_Widget_Pricing:: widget( $widget_args );
                                        }
                                    ?>
                                    </div>

                                </div>

                            </div>

                            <div class="col-12 col-lg-9">

                                <div class="form-hidden-fields d-none">
                                <?php

                                    print apply_filters( 'sdweddingdirectory/find-venue/hidden-input', [

                                        'id'    =>  $parent_id
                                    ] );

                                    parent:: venue_filter_hidden_input();
                                ?>
                                    <input autocomplete="off" type="hidden" name="layout" value="<?php echo absint( '8' ); ?>" />
                                </div>

                                <?php
                                    self:: display_query_result( array_merge( $page_args, [

                                        'layout'    =>  absint( '8' ),

                                        'have_map'  =>  false
                                    ] ) );
                                ?>

                            </div>

                        </div>

                    </form>

                </div>
                <?php

                get_footer();

                return;
            }

            ?>
            <div class="container-fluid gx-0">

                <div class="row gx-0" id="sdweddingdirectory_find_venue_section">

                    <!-- Content Area -->
                    <div class="<?php echo $have_map  ?  esc_attr( 'col-xxl-7 col-xl-12' )  :  esc_attr( 'col-xl-12' ); ?>">

                        <div class="venue-content <?php echo ! $have_map  ?  esc_attr( 'overflow-auto h-100' ) :  ''; ?>">

                            <!-- Search Wrap -->
                            <div class="search-wrap">

                                <div class="row align-items-center">

                                    <!-- Filter Box -->
                                    <div class="filter-form <?php echo $have_map ? esc_attr( 'col-12' ) : esc_attr( 'col-xxl-8 col-sm-12 col-12 mx-auto' ); ?>">

                                        <form class="sdweddingdirectory-result-page" method="post" action="<?php echo esc_url( $result_page ); ?>">

                                            <div class="row mb-2">

                                                <div class="col-xl-9 col-lg-8 col-md-12 col-12">

                                                    <div class="row sdweddingdirectory-dropdown-parent" id="<?php echo $parent_id; ?>">

                                                        <?php

                                                            print  

                                                            /**
                                                             *  Categoy Dropdown
                                                             *  ----------------
                                                             */
                                                            apply_filters( 'sdweddingdirectory/input-category',

                                                                array_merge( $query_args, [

                                                                    'before_input'      =>      '<div class="col-md-6 col-sm-12 col-12">',

                                                                    'after_input'       =>      '</div>',

                                                                    'parent_id'         =>      $parent_id

                                                                ] )
                                                            )

                                                            .

                                                            /**
                                                             *  Location Dropdown
                                                             *  -----------------
                                                             */
                                                            apply_filters( 'sdweddingdirectory/input-location',

                                                                array_merge( $query_args, [

                                                                    'before_input'      =>      '<div class="col-md-6 col-sm-12 col-12">',

                                                                    'after_input'       =>      '</div>',

                                                                    'parent_id'         =>      $parent_id

                                                                ] )
                                                            );

                                                        ?>

                                                    </div>

                                                </div>

                                                <!-- / Search + filter button -->
                                                <div class="col-xl-3 col-lg-4 col-sm-6 col-12">

                                                    <div class="row">

                                                        <div class="col" id="search_button_section">

                                                            <div class="d-grid">

                                                                <?php 

                                                                    printf( '<button class="btn btn-default btn-block btn-md" id="seach_result_btn" type="submit">%1$s</button>', 

                                                                        /**
                                                                         *  1. Translation Ready String
                                                                         *  ---------------------------
                                                                         */
                                                                        esc_attr__( 'Search', 'sdweddingdirectory' )

                                                                    );

                                                                ?>

                                                            </div>

                                                        </div>
                                                        <?php

                                                            /**
                                                             *  Have Filter Option ?
                                                             *  --------------------
                                                             */
                                                            self:: get_venue_filter_button( $query_args );

                                                        ?>

                                                        <div class="form-hidden-fields d-none">
                                                        <?php

                                                            /**
                                                             *  Write Hidden Query Input Field
                                                             *  ------------------------------
                                                             */
                                                            print   apply_filters( 'sdweddingdirectory/find-venue/hidden-input', [

                                                                        'id'        =>      $parent_id

                                                                    ] );

                                                            /**
                                                             *  Hidden Input
                                                             *  ------------
                                                             */
                                                            parent:: venue_filter_hidden_input();

                                                        ?>
                                                        </div> 

                                                    </div>

                                                </div> 
                                                <!-- / Search + filter button -->

                                            </div>

                                        </form>

                                    </div>
                                    <!-- / Filter Box -->

                                </div>

                            </div>
                            <!-- Search Wrap -->

                            <?php

                                /**
                                 *  Content Area [ Load Venue ]
                                 *  -----------------------------
                                 */
                                self:: display_query_result( $page_args );
                                
                            ?>
                        </div>

                    </div>
                    <!-- Content Area -->
                    <?php

                        /**
                         *  Have Map ?
                         *  ----------
                         */
                        if( $have_map ){

                            ?>
                            <div class="col-xxl-5 col-xl-12" id="map_handler" data-map-id="sdweddingdirectory_find_venue_template" data-map-class="venue-map">
                                
                                <div id="sdweddingdirectory_find_venue_template" class="venue_location_tax_map"></div>

                            </div>
                            <?php
                        }

                    ?>
                </div>
                <?php

            /**
             *  SDWeddingDirectory - Search Result Page Footer
             *  --------------------------------------
             */
            ?></div><?php

            /**
             *  Footer
             *  ------
             */
            get_footer();
        }

        /**
         *  Get Method wise display result
         *  ------------------------------
         */
        public static function display_query_result( $query_args = [] ){

            /**
             *  Extract Args
             *  ------------
             */
            extract( wp_parse_args( $query_args, [

                'layout'        =>      absint( '0' ),

            ] ) );

            /**
             *  Extract Query
             *  -------------
             */
            extract( $query_args );

            /**
             *  Venue Container
             *  -----------------
             */
            $result_data                        =       self:: display_result( [

                'category_id'                   =>      ! empty(  $cat_id  )    ?   $cat_id          :      false,

                'geoloc_enable'                 =>      ! empty( $geoloc )      ?   true             :      false,

                'city_name'                     =>      ! empty( $city_name )   ?   $city_name       :      '',

                'state_name'                    =>      ! empty( $state_name )  ?   $state_name      :      '',

                'region_name'                   =>      ! empty( $region_name ) ?   $region_name     :      '',

                'city_id'                       =>      ! empty( $city_id )     ?   $city_id         :      absint( '0' ),

                'region_id'                     =>      ! empty( $region_id )   ?   $region_id       :      absint( '0' ),

                'state_id'                      =>      ! empty( $state_id )    ?   $state_id        :      absint( '0' ),

                'location_slug'                 =>      ! empty( $location )    ?   sanitize_title( $location ) : '',

                'sub_category'                  =>      isset( $_GET['sub-category'] ) && ! empty( $_GET['sub-category'] )

                                                        ?   $_GET['sub-category']

                                                        :   false,

                'min_price'                     =>      isset( $_GET[ 'price-filter' ] ) && ! empty( $_GET[ 'price-filter' ] )

                                                        ?   min( self:: query_to_array( $_GET[ 'price-filter' ] ) )

                                                        :   absint( '0' ),

                'max_price'                     =>      isset( $_GET[ 'price-filter' ] ) && ! empty( $_GET[ 'price-filter' ] )

                                                        ?   max( self:: query_to_array( $_GET[ 'price-filter' ] ) )

                                                        :   absint( '0' ),

                'min_seat'                      =>      isset( $_GET[ 'capacity' ] ) && 

                                                        ! empty( $_GET[ 'capacity' ] )

                                                        ?   min( self:: query_to_array( $_GET[ 'capacity' ] ) )

                                                        :   absint( '0' ),

                'max_seat'                      =>      isset( $_GET[ 'capacity' ] ) && 

                                                        ! empty( $_GET[ 'capacity' ] )

                                                        ?   max( self:: query_to_array( $_GET[ 'capacity' ] ) )

                                                        :   absint( '0' ),

                'paged'                         =>      isset( $_GET[ 'paged' ] )  &&   ! empty( $_GET[ 'paged' ] )

                                                        ?   absint( $_GET[ 'paged' ] )

                                                        :   absint( '1' ),

                'per_page'                      =>      apply_filters( 'sdweddingdirectory/find-venue/post-per-page', absint( '12' ) ),

                'vendor_id'                     =>      isset( $_GET[ 'vendor_id' ] ) && ! empty( $_GET[ 'vendor_id' ] )

                                                        ?   absint( $_GET[ 'vendor_id' ] )

                                                        :   absint( '0' ),

                'sort_by'                       =>      isset( $_GET[ 'sort-by' ] ) && ! empty( $_GET[ 'sort-by' ] )

                                                        ?   $_GET[ 'sort-by' ]

                                                        :   absint( '0' ),

                'availability'                  =>      isset( $_GET[ 'availability' ] ) && ! empty( $_GET[ 'availability' ] )

                                                        ?   $_GET[ 'availability' ]

                                                        :   absint( '0' ),

                'term_box_group'                =>      isset( $_GET[ 'cat_id' ] )  &&  ! empty( $_GET[ 'cat_id' ] )

                                                        ?   self:: query_to_term_box( $_GET[ 'cat_id' ] )

                                                        :   self:: query_to_term_box( absint( '0' ) )
,

                'layout'                        =>      absint( $layout ),

            ] );

            /**
             *  Load Div
             *  --------
             */
            printf( '<div class="%1$s" id="sdweddingdirectory_venue_sorting">%2$s</div>

                    <div class="venue-post-wrap %3$s">

                        <div id="venue_search_result" class="row">%4$s</div>

                        <div id="venue_have_pagination" class="row text-center">%5$s</div>

                    </div>',

                    /**
                     *  1. Have Map ?
                     *  -------------
                     */
                    ! $have_map && ! self:: is_venues_search_page()  ?  esc_attr( 'col-xxl-8 col-sm-12 col-12 mx-auto' )  :  '',

                    /**
                     *  2. Layout
                     *  ---------
                     */
                    parent:: sdweddingdirectory_venue_layout( [

                        'layout'            =>      absint( $layout ),

                        'found_result'      =>      absint( $result_data[ 'found_result' ] )

                    ] ),

                    /**
                     *  3. Have Map ?
                     *  -------------
                     */
                    !  $have_map && ! self:: is_venues_search_page()  ?  esc_attr( 'col-xxl-8 col-sm-12 col-12 mx-auto' )   :   '',

                    /**
                     *  4. Display Data
                     *  ---------------
                     */
                    !   empty( $result_data[ 'venue_html_data' ] )

                    ?   $result_data[ 'venue_html_data' ]

                    :   '',

                    /**
                     *  5. Display Pagination
                     *  ---------------------
                     */
                    !   empty( $result_data[ 'pagination' ] )

                    ?   $result_data[ 'pagination' ]

                    :   ''
            );
        }

        /**
         *  Return Array
         *  ------------
         *  This function [ 0-10 ], [ 10-20 ] convert in []
         *  -----------------------------------------------
         */
        public static function query_to_array( $quer_args = '' ){

            /**
             *  Is Empty!
             *  ---------
             */
            if( empty( $quer_args ) ){

                return;
            }

            /**
             *  Have Args ?
             *  -----------
             */
            return      explode( '-', str_replace(

                            [ ',' ], 

                            '-', 

                            str_replace( [ '[', ']' ], '', $quer_args )

                        ) );
        }

        /**
         *  Category ID wise get term list and check is exists
         *  --------------------------------------------------
         */
        public static function query_to_term_box( $cat_id = '' ){

            /**
             *  Extract
             *  -------
             */
            $taxonomy      =      esc_attr( 'venue-type' );

            /**
             *  Dynamic Term Handler
             *  --------------------
             */
            $handler        =   [];

            /**
             *  Have Category
             *  -------------
             */
            if( ! empty( $cat_id ) ){

                /**
                 *  Collection of Group Box Args
                 *  ----------------------------
                 */
                $acf_group_box  =   apply_filters( 'sdweddingdirectory/dynamic-acf-group-box', [] );

                /**
                 *  Make sure acf group box not empty!
                 *  ----------------------------------
                 */
                if( parent:: _is_array( $acf_group_box ) ){

                    /**
                     *  Loop
                     *  ----
                     */
                    foreach( $acf_group_box as $group_box_key => $group_box_data ){

                        /**
                         *  Group Box Data
                         *  --------------
                         */
                        extract( $group_box_data );

                        /**
                         *  Term Post ID
                         *  ------------
                         */
                        $term_post_id       =       esc_attr( $taxonomy ) .'_'. absint( $cat_id );

                        /**
                         *  Enable Section & Filter Widget
                         *  ------------------------------
                         */
                        $enable_section     =       sdwd_get_term_field(  sanitize_key( 'enable_' . $slug  ), $cat_id );

                        $enable_filter      =       sdwd_get_term_field(  sanitize_key( 'filter_widget_' . $slug  ), $cat_id );

                        /**
                         *  Make sure admin enable this section
                         *  -----------------------------------
                         */
                        if( $enable_section && $enable_filter ){

                            /**
                             *  Checkbox Data
                             *  -------------
                             */
                            $_have_options      =       apply_filters( 'sdweddingdirectory/term-box-group', [

                                                            'term_id'       =>      absint( $cat_id ),

                                                            'slug'          =>      esc_attr( $slug )

                                                        ] );
                            /**
                             *  Have Options
                             *  ------------
                             */
                            if( parent:: _is_array( $_have_options ) && isset( $_GET[ $slug ] ) && $_GET[ $slug ] != '' ){

                                $handler[ $slug ]   =   $_GET[ $slug ];
                            }
                        }
                    }
                }

                /**
                 *  Setting Filter
                 *  --------------
                 */
                $setting_available = sdwd_get_term_field( 'setting_available', $cat_id );

                if( $setting_available && isset( $_GET[ 'venue_setting' ] ) && $_GET[ 'venue_setting' ] != '' ){

                    $handler[ 'venue_setting' ] = $_GET[ 'venue_setting' ];
                }

                /**
                 *  Amenities Filter
                 *  ----------------
                 */
                $amenities_available = sdwd_get_term_field( 'amenities_available', $cat_id );

                if( $amenities_available && isset( $_GET[ 'venue_amenities' ] ) && $_GET[ 'venue_amenities' ] != '' ){

                    $handler[ 'venue_amenities' ] = $_GET[ 'venue_amenities' ];
                }
            }

            /**
             *  Category Not Selected
             *  ---------------------
             */
            else{

                if( isset( $_GET[ 'venue_setting' ] ) && $_GET[ 'venue_setting' ] !== '' ){

                    $raw_setting = wp_unslash( $_GET[ 'venue_setting' ] );

                    $setting_values = is_array( $raw_setting )

                                        ? array_map( 'sanitize_text_field', $raw_setting )

                                        : parent:: _coma_to_array( sanitize_text_field( $raw_setting ) );

                    $setting_values = array_values( array_filter( $setting_values ) );

                    if( parent:: _is_array( $setting_values ) ){

                        $handler[ 'venue_setting' ] = implode( ',', $setting_values );
                    }
                }

                if( isset( $_GET[ 'venue_amenities' ] ) && $_GET[ 'venue_amenities' ] !== '' ){

                    $raw_amenities = wp_unslash( $_GET[ 'venue_amenities' ] );

                    $amenities_values = is_array( $raw_amenities )

                                        ? array_map( 'sanitize_text_field', $raw_amenities )

                                        : parent:: _coma_to_array( sanitize_text_field( $raw_amenities ) );

                    $amenities_values = array_values( array_filter( $amenities_values ) );

                    if( parent:: _is_array( $amenities_values ) ){

                        $handler[ 'venue_amenities' ] = implode( ',', $amenities_values );
                    }
                }
            }

            /**
             *  Found Query
             *  -----------
             */
            if( parent:: _is_array( $handler ) ){

                return      $handler;
            }
        }

        /**
         *  Sort By Venues
         *  ----------------
         */
        public static function sort_by_venue( $args = [] ){

            /**
             *  Is Array ?
             *  ----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'sort_by'       =>      '',

                    'post_ids'      =>      [],

                    'handler'       =>      []

                ] ) );

                /**
                 *  Make sure it's not empty!
                 *  -------------------------
                 */
                if( empty( $sort_by ) ){

                    return  $post_ids;
                }

                /**
                 *  Is Array ?
                 *  ----------
                 */
                if( parent:: _is_array( $post_ids ) ){

                    /**
                     *  Collection
                     *  ----------
                     */
                    foreach ( $post_ids as $key ){

                        /**
                         *  1. Most Rated Venue + Highest Rating
                         *  --------------------------------------
                         */
                        if( $sort_by == esc_attr( 'top_rated' ) ){

                            $handler[ absint( $key ) ]      =       apply_filters( 'sdweddingdirectory/rating/found', '', [

                                                                        'venue_id'  =>  absint( $key ),

                                                                    ] );

                            arsort( $handler );
                        }

                        /**
                         *  2. Most Rated Venue + Highest Rating
                         *  --------------------------------------
                         */
                        elseif( $sort_by == esc_attr( 'highest_rating' ) ){

                            $handler[ absint( $key ) ]      =       apply_filters( 'sdweddingdirectory/rating/average', '', [

                                                                        'venue_id'  =>  absint( $key ),

                                                                    ] );

                            arsort( $handler );
                        }

                        /**
                         *  2. Lowest Rating
                         *  ----------------
                         */
                        elseif( $sort_by == esc_attr( 'lowest_rating' ) ){

                            /**
                             *  Lowest Rating
                             *  -------------
                             */
                            $handler[ absint( $key ) ]      =       apply_filters( 'sdweddingdirectory/rating/average', '', [

                                                                        'venue_id'  =>  absint( $key ),

                                                                    ] );
                            asort( $handler );
                        }

                        /**
                         *  4. Highest Price
                         *  ----------------
                         */
                        elseif( $sort_by == esc_attr( 'most_viewed' ) ){

                            $handler[ absint( $key ) ]      =       absint( get_post_meta( absint( $key ), sanitize_key( 'venue_page_view' ), true ) );

                            arsort( $handler );
                        }

                        /**
                         *  4. Highest Price
                         *  ----------------
                         */
                        elseif( $sort_by == esc_attr( 'highest_price' ) ){

                            $handler[ absint( $key ) ]      =       absint( get_post_meta( absint( $key ), sanitize_key( 'venue_max_price' ), true ) );

                            arsort( $handler );
                        }

                        /**
                         *  4. Lowest Price
                         *  ---------------
                         */
                        elseif( $sort_by == esc_attr( 'lowest_price' ) ){

                            $handler[ absint( $key ) ]      =       absint( get_post_meta( absint( $key ), sanitize_key( 'venue_min_price' ), true ) );

                            asort( $handler );
                        }
                    }

                    /**
                     *  Make sure : Filter to get data
                     *  ------------------------------
                     */
                    if( parent:: _is_array( $handler ) ){

                        return      array_flip( $handler );
                    }
                }
            }
        }

        /**
         *  Term Box Data
         *  -------------
         */
        public static function venue_term_group_box( $args = [] ){

            /**
             *  Is Array ?
             *  ----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'slug'          =>      '',

                    'post_ids'      =>      [],

                    'handler'       =>      [],

                    'get_value'     =>      []

                ] ) );

                /**
                 *  Is Array ?
                 *  ----------
                 */
                if( parent:: _is_array( $post_ids ) ){

                    /**
                     *  Collection
                     *  ----------
                     */
                    foreach ( $post_ids as $key ){

                        /**
                         *  Post Have Amenities ?
                         *  ---------------------
                         */
                        $_post_data    =   get_post_meta( absint( $key ), sanitize_key( $slug ), true );

                        /**
                         *   Have Amenities + Is JSON Data to check is array and have at lest 1 data ?
                         *   -------------------------------------------------------------------------
                         */
                        if( parent:: _is_array( $_post_data ) ){

                            /**
                             *  Get Diffrence between :: Backend Post Amenities + Form To Submit Amenities
                             *  --------------------------------------------------------------------------
                             */
                            $_have_diff          =      array_diff( $get_value, $_post_data );

                            /**
                             *  HAVE + SET Amenities Data ?
                             *  ---------------------------
                             */
                            if( count( $_have_diff ) == absint('0') ){

                                $handler[]  = absint( $key );
                            }
                        }
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
         *  Venue Available
         *  -----------------
         */
        public static function venue_non_availability_dates( $args = [] ){

            /**
             *  Is Array ?
             *  ----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'post_ids'      =>      [],

                    'availability'  =>      [],

                    'handler'       =>      [],

                    'meta_key'      =>      sanitize_key( 'non_availability_dates' )

                ] ) );

                /**
                 *  Is Array ?
                 *  ----------
                 */
                if( parent:: _is_array( $post_ids ) ){

                    /**
                     *  Collection
                     *  ----------
                     */
                    foreach ( $post_ids as $key ){

                        /**
                         *  Post Have Amenities ?
                         *  ---------------------
                         */
                        $_post_data    =   get_post_meta( absint( $key ), sanitize_key( $meta_key ), true );

                        /**
                         *  Checking Post Available ?
                         *  -------------------------
                         */
                        if( ! empty( $_post_data ) ){

                            /**
                             *  Get Diffrence between :: Backend Post Amenities + Form To Submit Amenities
                             *  --------------------------------------------------------------------------
                             */
                            $_have_diff          =      array_intersect( $availability, parent:: _coma_to_array( $_post_data ) );

                            /**
                             *  HAVE + SET Amenities Data ?
                             *  ---------------------------
                             */
                            if( count( $_have_diff ) == absint('0') ){

                                $handler[]  = absint( $key );
                            }
                        }

                        else{

                            $handler[]  = absint( $key );
                        }
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
         *  Taxonomy Name
         *  -------------
         */
        public static function location_tax_name( $term_id = 0 ){

            /**
             *  Category Object
             *  ---------------
             */
            $obj    =   get_term( $term_id, esc_attr( 'venue-location' ) );

            /**
             *  Make sure it's not empty!
             *  -------------------------
             */
            if( ! empty( $obj ) ){

                return      esc_attr( $obj->name );    
            }

            else{

                return;
            }            
        }

        /**
         *  Taxonomy Name
         *  -------------
         */
        public static function category_tax_name( $term_id = 0 ){

            /**
             *  Category Object
             *  ---------------
             */
            $obj    =   get_term( $term_id, esc_attr( 'venue-type' ) );

            /**
             *  Make sure it's not empty!
             *  -------------------------
             */
            if( ! empty( $obj ) ){

                return      esc_attr( $obj->name );    
            }

            else{

                return;
            }  
        }

        /**
         *  Find Venue Category Data Page
         *  -------------------------------
         */
        public static function sdweddingdirectory_load_venue_data(){

            /**
             *  Return Data
             *  -----------
             */
            die( json_encode( self:: display_result( [

                    'category_id'                   =>      isset( $_POST[ 'cat_id' ] )  &&  ! empty( $_POST[ 'cat_id' ] )

                                                            ?   $_POST['cat_id']

                                                            :   false,

                    'sub_category'                  =>      isset( $_POST['sub-category'] ) && ! empty( $_POST['sub-category'] )

                                                            ?   $_POST['sub-category']

                                                            :   false,

                    'geoloc_enable'                 =>      isset( $_POST[ 'geoloc' ] ) && ! empty( $_POST[ 'geoloc' ] ) && $_POST[ 'geoloc' ] == absint( '1' )

                                                            ?       true

                                                            :       false,

                    'city_name'                     =>      isset( $_POST[ 'city_name' ] ) && ! empty( $_POST[ 'city_name' ] )

                                                            ?   esc_attr( $_POST[ 'city_name' ] )

                                                            :   '',

                    'region_name'                   =>      isset( $_POST[ 'region_name' ] ) && ! empty( $_POST[ 'region_name' ] )

                                                            ?   esc_attr( $_POST[ 'region_name' ] )

                                                            :   '',

                    'state_name'                    =>      isset( $_POST[ 'state_name' ] ) && ! empty( $_POST[ 'state_name' ] )

                                                            ?   esc_attr( $_POST[ 'state_name' ] )

                                                            :   '',

                    'city_id'                       =>      isset( $_POST[ 'city_id' ] ) && ! empty( $_POST[ 'city_id' ] )

                                                            ?   absint( $_POST[ 'city_id' ] )

                                                            :   absint( '0' ),

                    'region_id'                     =>      isset( $_POST[ 'region_id' ] ) && ! empty( $_POST[ 'region_id' ] )

                                                            ?   absint( $_POST[ 'region_id' ] )

                                                            :   absint( '0' ),

                    'state_id'                      =>      isset( $_POST[ 'state_id' ] ) && ! empty( $_POST[ 'state_id' ] )

                                                            ?   absint( $_POST[ 'state_id' ] )

                                                            :   absint( '0' ),

                    'location_slug'                 =>      isset( $_POST[ 'location' ] ) && ! empty( $_POST[ 'location' ] )

                                                            ?   sanitize_title( wp_unslash( $_POST[ 'location' ] ) )

                                                            :   '',

                    'min_price'                     =>      isset( $_POST[ 'min-price' ] ) && ! empty( $_POST[ 'min-price' ] )

                                                            ?   absint( $_POST[ 'min-price' ] )

                                                            :   absint( '0' ),

                    'max_price'                     =>      isset( $_POST[ 'max-price' ] ) && ! empty( $_POST[ 'max-price' ] )

                                                            ?   absint( $_POST[ 'max-price' ] )

                                                            :   absint( '0' ),

                    'min_seat'                      =>      isset( $_POST[ 'min-seat' ] ) && ! empty( $_POST[ 'min-seat' ] )

                                                            ?   absint( $_POST[ 'min-seat' ] )

                                                            :   absint( '0' ),

                    'max_seat'                      =>      isset( $_POST[ 'max-seat' ] ) && ! empty( $_POST[ 'max-seat' ] )

                                                            ?   absint( $_POST[ 'max-seat' ] )

                                                            :   absint( '0' ),

                    'paged'                         =>      isset( $_POST[ 'paged' ] )  &&   ! empty( $_POST[ 'paged' ] )

                                                            ?   absint( $_POST[ 'paged' ] )

                                                            :   absint( '1' ),

                    'per_page'                      =>      apply_filters( 'sdweddingdirectory/find-venue/post-per-page', absint( '12' ) ),

                    'vendor_id'                     =>      isset( $_POST[ 'vendor_id' ] ) && ! empty( $_POST[ 'vendor_id' ] )

                                                            ?   absint( $_POST[ 'vendor_id' ] )

                                                            :   absint( '0' ),

                    'sort_by'                       =>      isset( $_POST[ 'sort-by' ] ) && ! empty( $_POST[ 'sort-by' ] )

                                                            ?   $_POST[ 'sort-by' ]

                                                            :   absint( '0' ),

                    'availability'                  =>      isset( $_POST[ 'availability' ] ) && ! empty( $_POST[ 'availability' ] )

                                                            ?   $_POST[ 'availability' ]

                                                            :   absint( '0' ),

                    'term_box_group'                =>      isset( $_POST[ 'term_box_group' ] ) && parent:: _is_array( $_POST[ 'term_box_group' ] )

                                                            ?   $_POST[ 'term_box_group' ]

                                                            :   [],

                    'layout'                        =>      isset( $_POST[ 'layout' ] ) &&  ! empty( $_POST[ 'layout' ] )

                                                            ?   absint( $_POST[ 'layout' ] )

                                                            :   absint( '0' ),
            ] ) ) );
        }

        /**
         *  Reorder Venue IDs for Location-First Search
         *  -------------------------------------------
         */
        public static function prioritize_location_results( $args = [] ){

            extract( wp_parse_args( $args, [

                'post_ids'               =>      [],

                'selected_location_id'   =>      absint( '0' ),

                'location_taxonomy'      =>      sanitize_key( 'venue-location' ),

                'san_diego_slug'         =>      esc_attr( 'san-diego' ),
            ] ) );

            $all_ids = array_values( array_unique( array_map( 'absint', (array) $post_ids ) ) );

            if( ! parent:: _is_array( $all_ids ) ){

                return [
                    'ordered_ids'    =>  [],
                    'primary_ids'    =>  [],
                ];
            }

            $selected_location_id = absint( $selected_location_id );

            $san_diego_term       = get_term_by( 'slug', sanitize_title( $san_diego_slug ), sanitize_key( $location_taxonomy ) );

            $san_diego_term_id    = ! empty( $san_diego_term ) && ! is_wp_error( $san_diego_term )

                                    ?   absint( $san_diego_term->term_id )

                                    :   absint( '0' );

            $selected_ids         = [];

            if( ! empty( $selected_location_id ) ){

                $selected_ids = get_posts( [

                    'post_type'          =>  esc_attr( 'venue' ),

                    'post_status'        =>  esc_attr( 'publish' ),

                    'posts_per_page'     =>  -1,

                    'fields'             =>  esc_attr( 'ids' ),

                    'post__in'           =>  $all_ids,

                    'orderby'            =>  esc_attr( 'post__in' ),

                    'tax_query'          =>  [
                                                [
                                                    'taxonomy'  =>  sanitize_key( $location_taxonomy ),
                                                    'field'     =>  esc_attr( 'term_id' ),
                                                    'terms'     =>  [ $selected_location_id ],
                                                ]
                                            ],
                ] );
            }

            $selected_ids = array_values( array_unique( array_map( 'absint', (array) $selected_ids ) ) );

            $san_diego_ids = [];

            if( ! empty( $san_diego_term_id ) && ( empty( $selected_ids ) || $selected_location_id !== $san_diego_term_id ) ){

                $san_diego_ids = get_posts( [

                    'post_type'          =>  esc_attr( 'venue' ),

                    'post_status'        =>  esc_attr( 'publish' ),

                    'posts_per_page'     =>  -1,

                    'fields'             =>  esc_attr( 'ids' ),

                    'post__in'           =>  $all_ids,

                    'orderby'            =>  esc_attr( 'post__in' ),

                    'tax_query'          =>  [
                                                [
                                                    'taxonomy'  =>  sanitize_key( $location_taxonomy ),
                                                    'field'     =>  esc_attr( 'term_id' ),
                                                    'terms'     =>  [ $san_diego_term_id ],
                                                ]
                                            ],
                ] );
            }

            $san_diego_ids = array_values( array_unique( array_map( 'absint', (array) $san_diego_ids ) ) );

            $primary_ids   = ! empty( $selected_ids ) ? $selected_ids : $san_diego_ids;

            $ordered_ids   = $primary_ids;

            if( ! empty( $selected_ids ) && ! empty( $san_diego_ids ) ){

                $ordered_ids = array_values( array_unique( array_merge( $ordered_ids, $san_diego_ids ) ) );
            }

            $ordered_ids = array_values( array_unique( array_merge( $ordered_ids, array_diff( $all_ids, $ordered_ids ) ) ) );

            return [
                'ordered_ids'    =>  $ordered_ids,
                'primary_ids'    =>  $primary_ids,
            ];
        }

        /**
         *  Display Result
         *  --------------
         */
        public static function display_result( $args = [] ){

            /**
             *  If have data
             *  ------------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'meta_query'                    =>      [],

                    'tax_query'                     =>      [],

                    'filter_applying'               =>      [],

                    'category_id'                   =>      absint( '0' ),

                    'sub_category'                  =>      absint( '0' ),

                    'location_group_ids'            =>      [],

                    'location_group_name'           =>      [],

                    'location_input_id'             =>      '',

                    'location_input_name'           =>      '',

                    'geoloc_enable'                 =>      false,

                    'city_name'                     =>      '',

                    'region_name'                   =>      '',

                    'state_name'                    =>      '',

                    'city_id'                       =>      absint( '0' ),

                    'region_id'                     =>      absint( '0' ),

                    'state_id'                      =>      absint( '0' ),

                    'min_price'                     =>      absint( '0' ),

                    'max_price'                     =>      absint( '0' ),

                    'min_seat'                      =>      absint( '0' ),

                    'max_seat'                      =>      absint( '0' ),

                    'paged'                         =>      absint( '1' ),

                    'per_page'                      =>      apply_filters( 'sdweddingdirectory/find-venue/post-per-page', absint( '12' ) ),

                    'vendor_id'                     =>      absint( '0' ),

                    'sort_by'                       =>      absint( '0' ),

                    'availability'                  =>      absint( '0' ),

                    'term_box_group'                =>      [],

                    'term_post'                     =>      [],

                    'filter_applying'               =>      [],

                    'data'                          =>      '',

                    'venue_tab_one'               =>      '',

                    'venue_tab_two'               =>      '',

                    'venue_tab_three'             =>      '',

                    'layout'                        =>      absint( '0' ),

                    'location_taxonomy'             =>      sanitize_key( 'venue-location' ),

                    'location_id'                   =>      '',

                    'location_name'                 =>      '',

                    'location_slug'                 =>      ''

                ] ) );

                $location_priority_enabled = self:: is_venues_search_page() && (
                                                ! empty( $location_slug ) ||
                                                ! empty( $state_id ) ||
                                                ! empty( $city_name ) ||
                                                ! empty( $region_name ) ||
                                                ! empty( $state_name )
                                            );

                $location_priority_ids     = [];

                $matched_results_count     = absint( '0' );

                /**
                 *  Have Category ID ?
                 *  ------------------
                 */
                if( ! empty( $category_id ) ){

                    $tax_query[]    =   array(

                                            'taxonomy'      =>      esc_attr( 'venue-type' ),

                                            'terms'         =>      array( $category_id )
                                        );
                }

                /**
                 *  Have Sub Category IDs ?
                 *  -----------------------
                 */
                if( ! empty( $sub_category ) ){

                    $tax_query[]    =   array(

                                            'taxonomy'      =>      esc_attr( 'venue-type' ),

                                            'terms'         =>      preg_split ("/\,/", $sub_category )
                                        );
                }

                /**
                 *  Live Location Enabled ?
                 *  -----------------------
                 *  If Yes - Thene Search Location in Databse City, Region Name Wise
                 *  ----------------------------------------------------------------
                 */
                if( ! empty( $geoloc_enable ) ){

                    /**
                     *  Have City Name ?
                     *  ----------------
                     */
                    if( ! empty( $city_name ) ){

                        $location_group_name[]     =    esc_attr( $city_name );

                        $location_name       =    esc_attr( $city_name );
                    }

                    elseif( ! empty( $region_name ) ){

                        $location_group_name[]     =   esc_attr( $region_name );

                        $location_name       =    esc_attr( $region_name );
                    }

                    elseif( ! empty( $state_name ) ){

                        $location_group_name[]     =   esc_attr( $state_name );

                        $location_name       =    esc_attr( $state_name );
                    }

                    /**
                     *  Location Name Group Found ?
                     *  ---------------------------
                     */
                    if( parent:: _is_array( $location_group_name ) ){

                        if( self:: is_venues_search_page() && $location_priority_enabled ){

                            if( empty( $location_id ) && ! empty( $location_name ) ){

                                $location_term = get_term_by( 'name', esc_attr( $location_name ), esc_attr( $location_taxonomy ) );

                                if( ! empty( $location_term ) && ! is_wp_error( $location_term ) ){

                                    $location_id = absint( $location_term->term_id );
                                }
                            }

                        }else{

                            $tax_query[]    =       array(

                                                        'taxonomy'      =>      esc_attr( $location_taxonomy ),

                                                        'terms'         =>      $location_group_name,

                                                        'field'         =>      esc_attr( 'name' )
                                                    );
                        }
                    }
                }

                /**
                 *  Location will find ID wise
                 *  --------------------------
                 */
                else{

                    /**
                     *  Location Slug Exists ?
                     *  ----------------------
                     */
                    if( ! empty( $location_slug ) ){

                        $location_term = get_term_by( 'slug', sanitize_title( $location_slug ), esc_attr( $location_taxonomy ) );

                        if( ! empty( $location_term ) && ! is_wp_error( $location_term ) ){

                            $location_id    = absint( $location_term->term_id );

                            $location_name  = esc_attr( $location_term->name );
                        }
                    }

                    /**
                     *  State ID Exists ?
                     *  -----------------
                     */
                    if( empty( $location_id ) && ! empty( $state_id ) ){

                        /**
                         *  Have State ID
                         *  -------------
                         */
                        $state_id       =       apply_filters( 'sdweddingdirectory/term-id/exists', [

                                                    'term_id'       =>      absint( $state_id )

                                                ] );

                        /**
                         *  Make sure this term id is valid
                         *  -------------------------------
                         */
                        if( ! empty( $state_id ) ){

                            /**
                             *  Get State ID
                             *  ------------
                             */
                            $location_id           =   absint( $state_id );

                            $location_name         =   esc_attr( self:: location_tax_name( $state_id ) );

                            /**
                             *  Region ID Exists ?
                             *  ------------------
                             */
                            if( !  empty( $region_id ) ){

                                /**
                                 *  Have State ID
                                 *  -------------
                                 */
                                $region_id      =       apply_filters( 'sdweddingdirectory/term-id/exists', [

                                                            'term_id'       =>      absint( $region_id ),

                                                            'parent_id'     =>      absint( $state_id )

                                                        ] );

                                /**
                                 *  Make sure this term id is valid
                                 *  -------------------------------
                                 */
                                if( ! empty( $region_id ) ){

                                    /**
                                     *  Get Region ID
                                     *  -------------
                                     */
                                    $location_id           =   absint( $region_id );

                                    $location_name         =   esc_attr( self:: location_tax_name( $region_id ) );

                                    /**
                                     *  City ID Exists ?
                                     *  ----------------
                                     */
                                    if( ! empty( $city_id ) ){

                                        /**
                                         *  Have State ID
                                         *  -------------
                                         */
                                        $city_id    =       apply_filters( 'sdweddingdirectory/term-id/exists', [

                                                                'term_id'       =>      absint( $city_id ),

                                                                'parent_id'     =>      absint( $region_id )

                                                            ] );

                                        /**
                                         *  Make sure this term id is valid
                                         *  -------------------------------
                                         */
                                        if( ! empty( $city_id ) ){

                                            /**
                                             *  Get City ID
                                             *  -----------
                                             */
                                            $location_id           =   absint( $city_id );

                                            $location_name         =   esc_attr( self:: location_tax_name( $city_id ) );
                                        }
                                    }
                                }
                            }                            
                        }
                    }

                    /**
                     *  Have Location
                     *  -------------
                     */
                    if( ! empty( $location_id ) ){

                        if( self:: is_venues_search_page() && $location_priority_enabled ){

                            $location_priority_enabled = true;

                        }else{

                            $tax_query[]    =   [
                                                   'taxonomy'       =>      esc_attr( $location_taxonomy ),

                                                    'terms'         =>      $location_id,
                                                ];
                        }
                    }
                }

                /**
                 *  Venue Min & Max Price Filter
                 *  ------------------------------
                 */
                if( ! empty( $min_price ) && ! empty( $max_price ) ){

                    /**
                     *  Venue Min Price Filter
                     *  ------------------------
                     */
                    $meta_query[]       =   array(

                                                'key'           =>      esc_attr( 'venue_min_price' ),

                                                'type'          =>      esc_attr( 'numeric' ),

                                                'compare'       =>      esc_attr( 'BETWEEN' ),

                                                'value'         =>      [ $min_price, $max_price ]
                                            );
                }

                /**
                 *  Make sure query pass
                 *  --------------------
                 */
                if( ! empty( $min_seat ) && ! empty( $max_seat ) ){

                    /**
                     *  Seating Query
                     *  -------------
                     */
                    $meta_query[]       =   array(

                                               'key'        =>      esc_attr( 'venue_seat_capacity' ),

                                                'type'          =>      esc_attr( 'numeric' ),

                                                'compare'       =>      esc_attr( 'BETWEEN' ),

                                                'value'         =>      [ $min_seat, $max_seat ]
                                            );
                }

                /**
                 *  Venue Setting Filter
                 *  ----------------------
                 */
                if( isset( $_GET[ 'venue_setting' ] ) && $_GET[ 'venue_setting' ] !== '' ){

                    $raw_setting = wp_unslash( $_GET[ 'venue_setting' ] );

                    $setting_values = is_array( $raw_setting )

                                        ? array_map( 'sanitize_text_field', $raw_setting )

                                        : parent:: _coma_to_array( sanitize_text_field( $raw_setting ) );

                    $setting_values = array_values( array_filter( $setting_values ) );

                    if( parent:: _is_array( $setting_values ) ){

                        foreach( $setting_values as $setting_value ){

                            $meta_query[] = array(

                                'key'       =>  esc_attr( 'venue_setting' ),

                                'compare'   =>  esc_attr( 'LIKE' ),

                                'value'     =>  '"' . esc_attr( $setting_value ) . '"'
                            );
                        }
                    }
                }

                /**
                 *  Venue Amenities Filter
                 *  ------------------------
                 */
                if( isset( $_GET[ 'venue_amenities' ] ) && $_GET[ 'venue_amenities' ] !== '' ){

                    $raw_amenities = wp_unslash( $_GET[ 'venue_amenities' ] );

                    $amenities_values = is_array( $raw_amenities )

                                            ? array_map( 'sanitize_text_field', $raw_amenities )

                                            : parent:: _coma_to_array( sanitize_text_field( $raw_amenities ) );

                    $amenities_values = array_values( array_filter( $amenities_values ) );

                    if( parent:: _is_array( $amenities_values ) ){

                        foreach( $amenities_values as $amenities_value ){

                            $meta_query[] = array(

                                'key'       =>  esc_attr( 'venue_amenities' ),

                                'compare'   =>  esc_attr( 'LIKE' ),

                                'value'     =>  '"' . esc_attr( $amenities_value ) . '"'
                            );
                        }
                    }
                }


                /**
                 *  Find Venue Query ( matching set )
                 *  --------------------------------
                 */
                $args               =   array_merge(

                    /**
                     *  Default args
                     *  ------------
                     */
                    array(

                        'post_type'             =>      esc_attr( 'venue' ),

                        'post_status'           =>      esc_attr( 'publish' ),

                        'posts_per_page'        =>      -1,

                        'orderby'               =>      esc_attr( 'rand' )
                    ),

                    /**
                     *  Have Vendor Name in Query String ?
                     *  ----------------------------------
                     */
                    !   empty( $vendor_id )     ?   [  'author'   =>   absint( $vendor_id )  ]  :   [],

                    /**
                     *  2. If Have Meta Query ?
                     *  -----------------------
                     */
                    parent:: _is_array( $meta_query ) 

                    ?   array(

                            'meta_query'        => array(

                                'relation'  => 'AND',

                                $meta_query,
                            )
                        )

                    :   [],

                    /**
                     *  3. If Have Taxonomy Query ?
                     *  ---------------------------
                     */
                    parent:: _is_array( $tax_query ) 

                    ?   array(

                            'tax_query'        => array(

                                'relation'  => 'AND',

                                $tax_query,
                            )
                        )

                    :   []
                );

                /**
                 *  1) Filtered Venue IDs
                 *  ---------------------
                 */
                $collection                     =       new WP_Query( $args );
                $_post_id_collaction            =       wp_list_pluck( $collection->posts, 'ID' );
                $_post_id_collaction            =       array_values( array_unique( array_map( 'absint', (array) $_post_id_collaction ) ) );

                /**
                 *  Make sure have matching collection
                 *  ----------------------------------
                 */
                if( parent:: _is_array( $_post_id_collaction ) ){

                    /**
                     *  Have Any Sorting ?
                     *  ------------------
                     */
                    if( ! empty( $sort_by ) ){

                        $_post_id_collaction    =       self:: sort_by_venue( [

                                                            'sort_by'       =>      esc_attr( $sort_by ),

                                                            'post_ids'      =>      $_post_id_collaction

                                                        ] );
                    }

                    /**
                     *  Sort By not set then applying the badge priority
                     *  ------------------------------------------------
                     */
                    else{

                        $_post_id_collaction    =       apply_filters( 'sdweddingdirectory/venue/badge-filter', $_post_id_collaction );
                    }

                    /**
                     *  Location Search Ordering
                     *  ------------------------
                     *  Selected city first, then San Diego, then all remaining venues.
                     */
                    if( self:: is_venues_search_page() && $location_priority_enabled ){

                        $_location_priority = self:: prioritize_location_results( [

                            'post_ids'               =>  $_post_id_collaction,

                            'selected_location_id'   =>  absint( $location_id ),

                            'location_taxonomy'      =>  esc_attr( $location_taxonomy ),
                        ] );

                        if( isset( $_location_priority['ordered_ids'] ) && parent:: _is_array( $_location_priority['ordered_ids'] ) ){

                            $_post_id_collaction = array_values( array_map( 'absint', $_location_priority['ordered_ids'] ) );
                        }

                        if( isset( $_location_priority['primary_ids'] ) && parent:: _is_array( $_location_priority['primary_ids'] ) ){

                            $location_priority_ids = array_values( array_map( 'absint', $_location_priority['primary_ids'] ) );
                        }
                    }
                }

                /**
                 *  Apply availability + term-box filters on matching set
                 *  -----------------------------------------------------
                 */
                if( parent:: _is_array( $_post_id_collaction ) && ! empty( $availability ) ){

                    $_available_date = parent:: _coma_to_array( $availability );

                    if( parent:: _is_array( $_available_date ) ){

                        $collection_of_post = self:: venue_non_availability_dates( [

                                                'post_ids'      =>      $_post_id_collaction,

                                                'availability'  =>      $_available_date

                                            ] );

                        $_post_id_collaction = array_values( array_intersect( (array) $collection_of_post, (array) $_post_id_collaction ) );
                    }
                }

                if( parent:: _is_array( $_post_id_collaction ) && parent:: _is_array( $term_box_group ) ){

                    $_term_match_group = [];

                    foreach( $term_box_group as $term_meta_slug => $term_meta_value ){

                        $_term_match_group[] = self:: venue_term_group_box( [

                                                    'slug'          =>      $term_meta_slug,

                                                    'post_ids'      =>      $_post_id_collaction,

                                                    'get_value'     =>      preg_split ("/\,/", $term_meta_value )

                                                ] );
                    }

                    if( parent:: _is_array( $_term_match_group ) && count( $_term_match_group ) >= absint( '2' ) ){

                        $_post_id_collaction = array_values( call_user_func_array( 'array_intersect', $_term_match_group ) );
                    }

                    elseif( parent:: _is_array( $_term_match_group ) ){

                        $_post_id_collaction = array_values( array_intersect( $_post_id_collaction, $_term_match_group[ absint( '0' ) ] ) );
                    }
                }

                $_post_id_collaction    =   array_values( array_unique( array_map( 'absint', (array) $_post_id_collaction ) ) );
                $matched_results_count  =   count( $_post_id_collaction );

                /**
                 *  2) All Venue IDs
                 *  ----------------
                 */
                $_all_venue_ids = get_posts( [

                    'post_type'          =>  esc_attr( 'venue' ),

                    'post_status'        =>  esc_attr( 'publish' ),

                    'posts_per_page'     =>  -1,

                    'fields'             =>  esc_attr( 'ids' ),

                    'orderby'            =>  esc_attr( 'rand' ),
                ] );

                $_all_venue_ids = array_values( array_unique( array_map( 'absint', (array) $_all_venue_ids ) ) );

                /**
                 *  If no match, prioritize San Diego first.
                 *  ----------------------------------------
                 */
                if( ! parent:: _is_array( $_post_id_collaction ) ){

                    $_san_diego_ids = [];

                    $san_diego_term = get_term_by( 'slug', sanitize_title( 'san-diego' ), esc_attr( $location_taxonomy ) );

                    if( ! empty( $san_diego_term ) && ! is_wp_error( $san_diego_term ) ){

                        $_san_diego_ids = get_posts( [

                            'post_type'          =>  esc_attr( 'venue' ),

                            'post_status'        =>  esc_attr( 'publish' ),

                            'posts_per_page'     =>  -1,

                            'fields'             =>  esc_attr( 'ids' ),

                            'tax_query'          =>  [
                                                        [
                                                            'taxonomy'  =>  esc_attr( $location_taxonomy ),
                                                            'field'     =>  esc_attr( 'term_id' ),
                                                            'terms'     =>  [ absint( $san_diego_term->term_id ) ],
                                                        ]
                                                    ],
                        ] );
                    }

                    if( ! parent:: _is_array( $_san_diego_ids ) ){

                        $_san_diego_ids = get_posts( [

                            'post_type'          =>  esc_attr( 'venue' ),

                            'post_status'        =>  esc_attr( 'publish' ),

                            'posts_per_page'     =>  -1,

                            'fields'             =>  esc_attr( 'ids' ),

                            'meta_query'         =>  [
                                                        'relation'  =>  'OR',
                                                        [
                                                            'key'       =>  esc_attr( 'venue_city' ),
                                                            'compare'   =>  esc_attr( 'LIKE' ),
                                                            'value'     =>  esc_attr( 'San Diego' ),
                                                        ],
                                                        [
                                                            'key'       =>  esc_attr( 'city_name' ),
                                                            'compare'   =>  esc_attr( 'LIKE' ),
                                                            'value'     =>  esc_attr( 'San Diego' ),
                                                        ],
                                                        [
                                                            'key'       =>  esc_attr( 'city' ),
                                                            'compare'   =>  esc_attr( 'LIKE' ),
                                                            'value'     =>  esc_attr( 'San Diego' ),
                                                        ],
                                                    ],
                        ] );
                    }

                    $_san_diego_ids = array_values( array_unique( array_map( 'absint', (array) $_san_diego_ids ) ) );

                    $_post_id_collaction = array_values(
                        array_unique(
                            array_merge(
                                $_san_diego_ids,
                                array_diff( $_all_venue_ids, $_san_diego_ids )
                            )
                        )
                    );

                    $location_priority_ids = $_san_diego_ids;
                }

                /**
                 *  Match first, then all remaining venues
                 *  --------------------------------------
                 */
                else{

                    $_post_id_collaction = array_values(
                        array_unique(
                            array_merge(
                                $_post_id_collaction,
                                array_diff( $_all_venue_ids, $_post_id_collaction )
                            )
                        )
                    );
                }

                /**
                 *  Final list query (always return all ordered venues)
                 *  ---------------------------------------------------
                 */
                $item               =   new WP_Query( [

                                                'post_type'          =>  esc_attr( 'venue' ),

                                                'post_status'        =>  esc_attr( 'publish' ),

                                                'post__in'           =>  $_post_id_collaction,

                                                'orderby'            =>  esc_attr( 'post__in' ),

                                                'posts_per_page'     =>  -1,
                                            ] );
                /**
                 *  Total Paged
                 *  -----------
                 */
                $item_total_page    =   absint( $item->max_num_pages );
                
                /**
                 *  Found Total Number of Venue
                 *  -----------------------------
                 */
                $total_element      =   $item->found_posts;

                /**
                 *  Have Venue at least 1 ?
                 *  -------------------------
                 */
                if( $total_element >= absint( '1' ) ){

                    /**
                     *  WP_Query to get post ids collection
                     *  -----------------------------------
                     */
                    $get_posts_ids     =       wp_list_pluck( $item->posts, 'ID' );

                    /**
                     *  Final ordered IDs are ready for render.
                     *  ---------------------------------------
                     */
                    $filter_applying    =   array_values( array_unique( array_map( 'absint', (array) $get_posts_ids ) ) );



                    /**
                     *  Get Venue Post Ids
                     *  --------------------
                     */
                    if( parent:: _is_array( $filter_applying ) ){

                        $location_priority_on_page = [];

                        $location_separator_printed = false;

                        if(
                            $layout == absint( '8' ) &&
                            self:: is_venues_search_page() &&
                            $location_priority_enabled &&
                            parent:: _is_array( $location_priority_ids )
                        ){

                            $location_priority_on_page = array_values(

                                array_intersect(
                                    array_map( 'absint', (array) $filter_applying ),
                                    array_map( 'absint', (array) $location_priority_ids )
                                )
                            );
                        }

                        /**
                         *  Get Venue
                         *  -----------
                         */
                        foreach ( $filter_applying as $key => $value ){

                            if( $layout == absint( '8' ) ){

                                $is_primary_location_item = in_array( absint( $value ), $location_priority_on_page, true );

                                if(
                                    self:: is_venues_search_page() &&
                                    $location_priority_enabled &&
                                    parent:: _is_array( $location_priority_on_page ) &&
                                    ! empty( $location_priority_on_page ) &&
                                    ! $location_separator_printed &&
                                    ! $is_primary_location_item
                                ){

                                    $venue_tab_three .= '<div class="col-12"><h3 class="sd-venue-separator">More Venues in San Diego County</h3></div>';

                                    $location_separator_printed = true;
                                }

                                $venue_tab_three  .=      sprintf( '<div class="col-12">%1$s</div>',

                                                                apply_filters( 'sdweddingdirectory/venue/post', [

                                                                    'layout'    =>  absint( '8' ),

                                                                    'post_id'   =>  absint( $value )

                                                                ] )
                                                            );
                            }

                            else{

                                /**
                                 *  Post
                                 *  ----
                                 */
                                $venue_tab_one    .=      sprintf( '<div class="col">%1$s</div>',

                                                                apply_filters( 'sdweddingdirectory/venue/post', [

                                                                    'layout'    =>  absint( '1' ),

                                                                    'post_id'   =>  absint( $value )

                                                                ] )
                                                            );
                                /**
                                 *  Post
                                 *  ----
                                 */
                                $venue_tab_two    .=      sprintf( '<div class="col">%1$s</div>',

                                                                apply_filters( 'sdweddingdirectory/venue/post', [

                                                                    'layout'    =>  absint( '2' ),

                                                                    'post_id'   =>  absint( $value )

                                                                ] )
                                                            );
                            }
                        }

                        /**
                         *  Return Data
                         *  -----------
                         */
                        if( $layout == absint( '8' ) ){

                            $data = sprintf( '<div class="row row-cols-1">%1$s</div>', $venue_tab_three );
                        }

                        else{

                            $data =    sprintf(   '<div class="tab-content" id="sdweddingdirectory-find-venue-tab-content">

                                                        <div class="tab-pane fade %1$s" id="%2$s" role="tabpanel" aria-labelledby="%2$s-tab">

                                                            <div class="row row-cols-xxl-3 row-cols-xl-3 row-cols-lg-2 row-cols-md-2 row-cols-sm-2 row-cols-1">%3$s</div>

                                                        </div>

                                                        <div class="tab-pane fade %4$s" id="%5$s" role="tabpanel" aria-labelledby="%5$s-tab">

                                                            <div class="row row-cols-1">%6$s</div>

                                                        </div>

                                                    </div>',

                                                    /**
                                                     *  1. Layout One ?
                                                     *  ---------------
                                                     */
                                                    $layout == absint( '1' )    ?   esc_attr( 'active show' )   :   '',

                                                    /**
                                                     *  2. Tab Content Name
                                                     *  -------------------
                                                     */
                                                    esc_attr( 'grid-view' ),

                                                    /**
                                                     *  3. Venue Data
                                                     *  ---------------
                                                     */
                                                    $venue_tab_one,

                                                    /**
                                                     *  4. Layout One ?
                                                     *  ---------------
                                                     */
                                                    $layout == absint( '0' )    ?   esc_attr( 'active show' )   :   '',

                                                    /**
                                                     *  5. Tab Content Name
                                                     *  -------------------
                                                     */
                                                    esc_attr( 'list-view' ),

                                                    /**
                                                     *  6. Venue Data
                                                     *  ---------------
                                                     */
                                                    $venue_tab_two
                                        );
                        }
                    }

                    /**
                     *  Make sure city id available
                     *  ---------------------------
                     */
                    if( ! empty( $city_id ) && ! empty( $region_id ) ){

                        /**
                         *  Sorted By
                         *  ---------
                         */
                        $_city_list     =       apply_filters( 'sdweddingdirectory/term-id/child-data', [

                                                    'term_id'           =>  $region_id,

                                                    '__return'          =>  'group'

                                                ] );

                        /**
                         *  Make sure city have data
                         *  ------------------------
                         */
                        if( parent:: _is_array( $_city_list ) ){

                            /**
                             *  Extract
                             *  -------
                             */
                            $city_obj       =   get_term( $city_id, esc_attr( $location_taxonomy ) );

                            $state_obj      =   get_term( $region_id, esc_attr( $location_taxonomy ) );

                            /**
                             *  Make sure object not empty!
                             *  ---------------------------
                             */
                            if( ! empty( $city_obj ) && ! empty( $state_obj ) ){

                                /**
                                 *  Removed Current City ID
                                 *  -----------------------
                                 */
                                unset( $_city_list[ $city_id ] );

                                /**
                                 *  Make sure city data available after current removed
                                 *  ---------------------------------------------------
                                 */
                                if( parent:: _is_array( $_city_list ) ){

                                    /**
                                     *  Merge City Data
                                     *  ---------------
                                     */
                                    $data      .= 

                                    sprintf(   '<div class="col-12">

                                                    <h3 class="mb-3">%1$s</h3>

                                                </div>

                                                <div class="col-12">

                                                    <div class="row row-cols-xxl-3 row-cols-xl-3 row-cols-lg-2 row-cols-md-2 row-cols-sm-2 row-cols-1">%2$s</div>

                                                </div>',

                                            /**
                                             *  1. Translation Ready String
                                             *  ---------------------------
                                             */
                                            sprintf( esc_attr__( 'Other options near %1$s, %2$s', 'sdweddingdirectory' ),

                                                /**
                                                 *  1. City Name
                                                 *  ------------
                                                 */
                                                esc_attr( $city_obj->name ),

                                                /**
                                                 *  2. State Name
                                                 *  -------------
                                                 */
                                                esc_attr( $state_obj->name )
                                            ),

                                            /**
                                             *  2. Load Venue Data
                                             *  --------------------
                                             */
                                            do_shortcode( sprintf( '[sdweddingdirectory_venue layout="1" posts_per_page="" location_id="%1$s"][/sdweddingdirectory_venue]',

                                                implode( ',', array_keys( $_city_list ) )

                                            ) )
                                    );
                                }
                            }
                        }
                    }

                    /** 
                     *  Have Venue Data ?
                     *  -------------------
                     */
                    if( ! empty( $data ) && parent:: _is_array( $filter_applying ) ){

                        return      [
                                        'venue_html_data'         =>      $data,

                                        'pagination'                =>      parent:: create_pagination(

                                                                                absint( $total_element ),

                                                                                absint( max( 1, $total_element ) ),

                                                                                absint( '1' )
                                                                            ),

                                        'found_result'              =>      absint( $matched_results_count ),

                                        'location_input_id'         =>      absint( $location_id ),

                                        'location_input_name'       =>      esc_attr( $location_name ),
                                    ];
                    }

                    /**
                     *  Empty Data
                     *  ----------
                     */
                    else{

                        return      parent:: venue_not_found();
                    }
                }

                /**
                 *  Empty Data
                 *  ----------
                 */
                else{

                    return      parent:: venue_not_found();
                }
            }
        }

        /**
         *  Search Button + Filter Button ( COMBO )
         *  ---------------------------------------
         */
        public static function get_venue_filter_button( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( $args );

                /**
                 *  Have any one filter exists ?
                 *  ----------------------------
                 */
                $_filter_button_enable      =   apply_filters( 'sdweddingdirectory/find-venue/enable-filter-button', [], $args );

                /**
                 *  By default filter disabled
                 *  --------------------------
                 */
                $_filter                    =   sanitize_html_class( 'd-none' );

                /**
                 *  Any one filter available ?
                 *  --------------------------
                 */
                if( parent:: _is_array( $_filter_button_enable ) && in_array( true, $_filter_button_enable ) ){

                    $_filter                =   sanitize_html_class( 'd-block' );
                }

                /**
                 *  Have Filter ?
                 *  -------------
                 */
                printf(    '<div class="col %1$s" id="filter_button_section">

                                <div class="d-grid">

                                    <button class="btn btn-primary btn-block btn-md sdweddingdirectory-filter-toggle" type="button" 

                                        data-bs-toggle="offcanvas" data-bs-target="#sidebar-filter">

                                            <i class="fa fa-sliders me-1" aria-hidden="true"></i> 

                                            <span>%2$s</span>
                                            <span class="sdweddingdirectory-filter-count badge bg-light text-dark ms-2 d-none" id="venue-filter-count"></span>

                                    </button>

                                </div>

                            </div>',

                            /**
                             *  1. Enable / Disable ?
                             *  ---------------------
                             */
                            sanitize_html_class( $_filter ),

                            /**
                             * 2. Translation Ready String
                             * ---------------------------
                             */
                            esc_attr__( 'Filters', 'sdweddingdirectory' )
                );
            }
        }
    }

    /**
    *  Kicking this off by calling 'get_instance()' method
    */
    SDWeddingDirectory_Search_Result::get_instance();
}
