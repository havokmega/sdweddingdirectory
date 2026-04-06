<?php
/**
 *  ------------------------------
 *  SDWeddingDirectory - Dropdown - Object
 *  ------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Dropdown_Script' ) && class_exists( 'SDWeddingDirectory_Front_End_Modules' ) ){

    /**
     *  ------------------------------
     *  SDWeddingDirectory - Dropdown - Object
     *  ------------------------------
     */
    class SDWeddingDirectory_Dropdown_Script extends SDWeddingDirectory_Front_End_Modules {

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
             *  Term Exists Check in Category and Location
             *  ------------------------------------------
             */
            add_filter( 'sdweddingdirectory/term_exists', [ $this, '_term_exists' ], absint( '10' ), absint( '1' ) );

            /**
             *  Load Script 
             *  -----------
             */
            add_action( 'wp_enqueue_scripts', array( $this, 'sdweddingdirectory_script' ), absint( '500' ) );

            /**
             *  Term id to get taxonomy name
             *  ----------------------------
             */
            add_filter( 'sdweddingdirectory/term-id/tax', [ $this, 'get_taxonomy_by_term_id' ], absint( '10' ), absint( '1' ) );

            /**
             *  Find Post Location Depth level ( City )
             *  ---------------------------------------
             */
            add_filter( 'sdweddingdirectory/find-post-location/name', [ $this, 'find_location_name_in_post' ], absint( '10' ), absint( '1' ) );

            /**
             *  Find Post Location Depth level ( City )
             *  ---------------------------------------
             */
            add_filter( 'sdweddingdirectory/find-post-location/id', [ $this, 'find_location_id_in_post' ], absint( '10' ), absint( '1' ) );

            /**
             *  Get Term ID wise JSON
             *  ---------------------
             */
            add_filter( 'sdweddingdirectory/term-id/json', [ $this, 'location_term_id_wise_collect_json' ], absint( '10' ), absint( '1' ) );

            /**
             *  Term ID to get Depth Level and Depth Level wise get area name
             *  -------------------------------------------------------------
             */
            add_filter( 'sdweddingdirectory/term-id/area-name', [ $this, 'location_depth_level_wise_area_name' ], absint( '10' ), absint( '1' ) );

            /**
             *  Load one by one file
             *  --------------------
             */
            foreach ( glob( plugin_dir_path( __FILE__ ) . '/*/index.php' ) as $file ) {
           
                require_once $file;
            }
        }

        /**
         *  Load Script 
         *  -----------
         */
        public static function sdweddingdirectory_script(){

            /**
             *  Have Request ?
             *  --------------
             */
            if( parent:: _load_script( 'sdweddingdirectory/enable-script/input-dropdown' ) ){

                /**
                 *  Load Script
                 *  -----------
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
                    esc_url(   plugin_dir_url( __FILE__ )   .   'script.js'   ),

                    /**
                     *  3. Load After "JQUERY" Load
                     *  ---------------------------
                     */
                    array( 'jquery' ),

                    /**
                     *  4. Bootstrap - Library Version
                     *  ------------------------------
                     */
                    esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ )   .   'script.js' ) ),

                    /**
                     *  5. Load in Footer
                     *  -----------------
                     */
                    true
                );
            }
        }

        /**
         *  Highlight String
         *  ----------------
         *  @link - https://www.codegrepper.com/code-examples/php/highlight+search+text+in+php
         *  ----------------------------------------------------------------------------------
         */
        public static function _string_highlight( $string, $word ){

            $text = preg_replace('#'. preg_quote($word) .'#i', '<span class="fw-bolder">\\0</span>', $string  );

            return  $text;
        }

        /**
         *  Have Word in String ?
         *  ---------------------
         */
        public static function _have_word( $string, $word ){

            /**
             *  String Found
             *  ------------
             */
            if ( str_contains( esc_attr( strtolower( $string ) ), strtolower( $word ) ) ) {

                return true;
            }

            else{

                return false;
            }
        }

        /**
         *  Term ID to get Taxonomy
         *  -----------------------
         */
        public static function get_taxonomy_by_term_id( $term_id ) {
            
            /**
             *  We can't get a term if we don't have a term ID
             *  ----------------------------------------------
             */
            if ( 0 === $term_id || null === $term_id ) {

                return      false;
            }
            
            /**
             *  Get Term
             *  --------
             */
            $term           =       get_term( $term_id );

            /**
             *  Is WP Error ?
             *  -------------
             */
            if( is_wp_error( $term ) ){

                return      false;
            }

            /**
             *  Make sure found object
             *  ----------------------
             */
            elseif ( ! empty( $term ) ) {

                return      trim( $term->taxonomy );
            }
        }

        /**
         *  Category + Location ID term Exist in venue post ?
         *  ---------------------------------------------------
         */
        public static function _term_exists( $args = [] ){

            /**
             *  Extract Args
             *  ------------
             */
            extract( wp_parse_args( $args, [

                'category_id'       =>      '',

                'location_id'       =>      '',

                'counter'           =>      false,

                'category_field'    =>      esc_attr( 'id' ),

                'location_field'    =>      esc_attr( 'id' ),

                'tax_query'         =>      [],

                'post_type'         =>      esc_attr( 'venue' )

            ] ) );

            /**
             *  Get Venue Category ID
             *  -----------------------
             */
            $category_tax    =   self:: get_taxonomy_by_term_id( $category_id );

            if( $category_id != '' && ! empty( $category_id ) && ! empty( $category_tax ) ){

                $tax_query[]    =   array(

                    'taxonomy'  =>  $category_tax,

                    'field'     =>  esc_attr( $category_field ),

                    'terms'     =>  $category_id
                );
            }

            /**
             *  Get Venue Location ID
             *  -----------------------
             */
            $location_tax    =   self:: get_taxonomy_by_term_id( $location_id );

            if( $location_id != '' && ! empty( $location_id ) && ! empty( $location_tax ) ){

                $tax_query[]    =   array(

                    'taxonomy'  =>  $location_tax,

                    'field'     =>  esc_attr( $location_field ),

                    'terms'     =>  $location_id
                );
            }

            /**
             *  Find Venue Query
             *  ------------------
             */
            $args           =   array_merge(

                /**
                 *  1. Default args
                 *  ---------------
                 */
                array(

                    'post_type'         =>  esc_attr( $post_type ),

                    'post_status'       =>  esc_attr( 'publish' ),

                    'posts_per_page'    =>  -1,
                ),

                /**
                 *  2. If Have Taxonomy Query ?
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
             *  WordPress to Find Query
             *  -----------------------
             */
            $item               =   new WP_Query( $args );
            
            /**
             *  Found Total Number of Venue
             *  -----------------------------
             */
            $total_element      =   $item->found_posts;

            /** 
             *  Reset Post Query
             *  ----------------
             */
            if( isset( $item ) ){ 

                wp_reset_postdata();
            }

            /**
             *  Return Counter ?
             *  ----------------
             */
            if( $counter ){

                return      absint( $total_element );
            }

            /**
             *  Have Venue at least 1 ?
             *  -------------------------
             */
            return      $total_element >= absint( '1' )     ?   true    :   false;
        }

        /**
         *  Get Location Area is City / Region / Country / State ?
         *  ------------------------------------------------------
         */
        public static function location_depth_level_wise_area_name( $args = [] ){

            /**
             *  Make sure have args 
             *  -------------------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'term_id'       =>      absint( '0' ),

                    'taxonomy'      =>      sanitize_key( 'venue-location' ),

                    'depth_level'   =>      absint( '3' ),

                    'handler'       =>      ''

                ] ) );

                /**
                 *  Make sure term id not emtpy!
                 *  ----------------------------
                 */
                if( empty( $term_id ) ){

                    return;
                }

                /**
                 *  Term id wise get object of parent
                 *  ---------------------------------
                 */
                /**
                 *  All venue-location terms are cities (flat hierarchy)
                 *  ---------------------------------------------------
                 */
                if( $taxonomy === 'venue-location' ){

                    $handler    =       esc_attr__( 'City', 'sdweddingdirectory' );

                } else {

                    $ancestors      =       get_ancestors( $term_id, $taxonomy );

                    if( parent:: _is_array( $ancestors ) ){

                        $depth_level    =   count( $ancestors ) + absint( '1' );
                    }

                    if( $depth_level == absint( '4' ) ) {

                        $handler    =       esc_attr__( 'City', 'sdweddingdirectory' );
                    }

                    elseif( $depth_level == absint( '3' ) ) {

                        $handler    =       esc_attr__( 'Region', 'sdweddingdirectory' );
                    }

                    elseif( $depth_level == absint( '2' ) ) {

                        $handler    =       esc_attr__( 'State', 'sdweddingdirectory' );
                    }

                    elseif( $depth_level == absint( '1' ) ) {

                        $handler    =       esc_attr__( 'Country', 'sdweddingdirectory' );
                    }
                }

                /**
                 *  Return Handler
                 *  --------------
                 */
                return          $handler;
            }
        }

        /**
         *  Get Term ID wise JSON
         *  ---------------------
         */
        public static function location_term_id_wise_collect_json( $args = [] ){

            /**
             *  Make sure have args 
             *  -------------------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'term_id'       =>      absint( '0' ),

                    'handler'       =>      '',

                    'collection'    =>      '',

                    'term_data'     =>      [],

                    'term_info'     =>      true,

                    'found_three'   =>      [ 'state_id', 'region_id', 'city_id' ],

                    'found_two'     =>      [ 'state_id', 'region_id' ],

                    'found_one'     =>      [ 'state_id' ],

                    'default'       =>      [ 'state_id' => '', 'region_id' => '', 'city_id' => '', 'location' => '' ],

                    'term_name'     =>      []

                ] ) );

                /**
                 *  Make sure term id not emtpy!
                 *  ----------------------------
                 */
                if( empty( $term_id ) ){

                    return          esc_html( json_encode( $default ) );
                }

                /**
                 *  Term Id to get Taxonomy
                 *  -----------------------
                 */
                $taxonomy       =       self:: get_taxonomy_by_term_id( $term_id );

                /**
                 *  Term id wise get object of parent
                 *  ---------------------------------
                 */
                $ancestors      =       get_ancestors( $term_id, $taxonomy );

                /**
                 *  Have Data ?
                 *  -----------
                 */
                if( parent:: _is_array( $ancestors ) ){

                    /**
                     *  Get Collection
                     *  --------------
                     */
                    $collection      =       array_reverse( array_merge( [ $term_id ], $ancestors ) );

                    /**
                     *  Is Array ?
                     *  ----------
                     */
                    if( parent:: _is_array( $collection ) ){

                        /**
                         *  Removed Country Term
                         *  --------------------
                         */
                        array_shift( $collection );

                        /**
                         *  Merge Default Args
                         *  ------------------
                         */
                        if( count( $collection ) == absint( '3' ) ){

                            $handler        =   array_combine( $found_three, $collection );

                            $term_name      =   self:: get_term_name( [

                                                    'term_ids'          =>      $collection,

                                                ] );

                        }

                        if( count( $collection ) == absint( '2' ) ){
                            
                            $handler        =   array_combine( $found_two, $collection );

                            $term_name      =   self:: get_term_name( [

                                                    'term_ids'          =>      $collection,

                                                ] );
                        }

                        if( count( $collection ) == absint( '1' ) ){
                        
                            $handler        =   array_combine( $found_one, $collection );

                            $term_name      =   self:: get_term_name( [

                                                    'term_ids'          =>      $collection,

                                                ] );
                        }
                    }
                }

                /**
                 *  Term Object
                 *  -----------
                 */
                $term_obj                       =       get_term( $term_id, esc_attr( $taxonomy ) );

                $term_data[ 'term_id' ]         =       absint( $term_obj->term_id );

                $term_data[ 'term_name' ]       =       esc_attr( $term_obj->name );

                $term_data[ 'location' ]        =       sanitize_title( $term_obj->slug );
                
                /**
                 *  Merge if key not exist 
                 *  ----------------------
                 */
                if( parent:: _is_array( $found_three ) && parent:: _is_array( $handler ) ){

                    foreach( $found_three as $value ){

                        if( ! array_key_exists( $value, $handler ) ){

                            $handler[ $value ]  =   '';
                        }
                    }
                }

                /**
                 *  Return Term Info
                 *  ----------------
                 */
                if( $term_info ){

                    /**
                     *  Have Args ?
                     *  -----------
                     */
                    if( parent:: _is_array( $handler ) ){

                        return     esc_html( json_encode( 

                                        array_merge( $term_data, $handler, $term_name )

                                    ) );
                    }
                }

                /**
                 *  Handler
                 *  -------
                 */
                else{

                    /**
                     *  Have Args ?
                     *  -----------
                     */
                    if( parent:: _is_array( $handler ) ){

                        return     esc_html( json_encode(  array_merge( $handler, $term_name )  ) );
                    }
                }
            }
        }

        /**
         *  Have Args ?
         *  -----------
         */
        public static function get_term_name( $args = [] ){

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

                    'term_ids'          =>      [],

                    'handler'           =>      [],

                    'term_group'        =>      [],

                    'found_three'       =>      [ 'state_name', 'region_name', 'city_name' ],

                    'found_two'         =>      [ 'state_name', 'region_name' ],

                    'found_one'         =>      [ 'state_name' ],

                    'default'           =>      [ 'state_name' => '', 'region_name' => '', 'city_name' => '' ],

                    'taxonomy'          =>      sanitize_key( 'venue-location' ),

                    'collection'        =>      []

                ] ) );

                /**
                 *  Have Term IDs
                 *  -------------
                 */
                if( parent:: _is_array( $term_ids ) ){

                    foreach( $term_ids as $key => $term_id ){

                        /**
                         *  Term Object
                         *  -----------
                         */
                        $term_obj               =       get_term( $term_id, esc_attr( $taxonomy ) );

                        if( ! empty( $term_obj ) ){

                            $handler[ $key ]        =       esc_attr( $term_obj->name );
                        }
                    }
                }

                /**
                 *  Merge Default Args
                 *  ------------------
                 */
                if( count( $handler ) == absint( '3' ) ){

                    $term_group    =   array_combine( $found_three, $handler );
                }

                if( count( $handler ) == absint( '2' ) ){
                    
                    $term_group    =   array_combine( $found_two, $handler );
                }

                if( count( $handler ) == absint( '1' ) ){
                
                    $term_group    =   array_combine( $found_one, $handler );
                }

                /**
                 *  Merge if key not exist 
                 *  ----------------------
                 */
                if( parent:: _is_array( $found_three ) && parent:: _is_array( $term_group ) ){

                    foreach( $found_three as $value ){

                        if( ! array_key_exists( $value, $term_group ) ){

                            $term_group[ $value ]  =   '';
                        }
                    }
                }


                /**
                 *  Term Group
                 *  ----------
                 */
                return      $term_group;
            }
        }


        /**
         *  Find Post location in depth - name
         *  ----------------------------------
         */
        public static function find_location_name_in_post( $args = [] ){

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

                    'post_id'       =>      absint( '0' ),

                    'taxonomy'      =>      esc_attr( 'venue-location' ),

                    'handler'       =>      []

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return;
                }

                /**
                 *  Get Post Object
                 *  ---------------
                 */
                $_obj   =   (array) wp_get_post_terms(

                                absint( $post_id ),

                                $taxonomy,

                                array( "fields" => "ids", 'orderby' => 'parent' )
                            );

                /**
                 *  Is Array ?
                 *  ----------
                 */
                if( parent:: _is_array( $_obj ) ){

                    foreach( $_obj as $key => $term_id ){

                        $parent_exist       =       get_ancestors( $term_id, $taxonomy );

                        if( parent:: _is_array( $parent_exist ) ){

                            $handler[ $term_id ]  =   count( $parent_exist );
                        }
                    }

                    /**
                     *  Have Data
                     *  ---------
                     */
                    if( parent:: _is_array( $handler ) ){

                        /**
                         *  Get Object
                         *  ----------
                         */
                        $object     =   get_term_by( 'id', absint( array_search( max( $handler ), $handler ) ), $taxonomy );

                        /**
                         *  Make sure object not empty!
                         *  ---------------------------
                         */
                        if( ! empty( $object ) ){

                            /**
                             *  Return Term Name
                             *  ----------------
                             */
                            return      esc_attr( $object->name );
                        }
                    }
                }
            }
        }

        /**
         *  Find Post location in depth - ID
         *  --------------------------------
         */
        public static function find_location_id_in_post( $args = [] ){

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

                    'post_id'       =>      absint( '0' ),

                    'taxonomy'      =>      esc_attr( 'venue-location' ),

                    'handler'       =>      []

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return;
                }

                /**
                 *  Get Post Object
                 *  ---------------
                 */
                $_obj   =   (array) wp_get_post_terms(

                                absint( $post_id ),

                                $taxonomy,

                                array( "fields" => "ids", 'orderby' => 'parent' )
                            );

                /**
                 *  Is Array ?
                 *  ----------
                 */
                if( parent:: _is_array( $_obj ) ){

                    foreach( $_obj as $key => $term_id ){

                        $parent_exist       =       get_ancestors( $term_id, $taxonomy );

                        if( parent:: _is_array( $parent_exist ) ){

                            $handler[ $term_id ]  =   count( $parent_exist );
                        }
                    }

                    /**
                     *  Have Data
                     *  ---------
                     */
                    if( parent:: _is_array( $handler ) ){

                        /**
                         *  Get Object
                         *  ----------
                         */
                        $object     =   get_term_by( 'id', absint( array_search( max( $handler ), $handler ) ), $taxonomy );

                        /**
                         *  Make sure object not empty!
                         *  ---------------------------
                         */
                        if( ! empty( $object ) ){

                            /**
                             *  Return Term Name
                             *  ----------------
                             */
                            return      esc_attr( $object->term_id );
                        }
                    }
                }
            }
        }
    }

    /**
     *  ------------------------------
     *  SDWeddingDirectory - Dropdown - Object
     *  ------------------------------
     */
    SDWeddingDirectory_Dropdown_Script::get_instance();
}
