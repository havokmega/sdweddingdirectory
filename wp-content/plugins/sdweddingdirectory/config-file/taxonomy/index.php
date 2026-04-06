<?php
/**
 *  SDWeddingDirectory Taxonomy Helper
 *  --------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Taxonomy' ) && class_exists( 'SDWeddingDirectory_Config' ) ){

    /**
     *  SDWeddingDirectory Taxonomy Helper
     *  --------------------------
     */
    class SDWeddingDirectory_Taxonomy extends SDWeddingDirectory_Config{

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
        public function __construct() {

            /**
             *  Taxonomy : Dropdown
             *  -------------------
             */
            add_filter( 'sdweddingdirectory/tax/all', [ $this, 'sdweddingdirectory_tax_all_terms' ], absint( '10' ), absint( '2' ) );

            /**
             *  Taxonomy : Dropdown
             *  -------------------
             */
            add_filter( 'sdweddingdirectory/tax/parent', [ $this, 'sdweddingdirectory_tax_parent' ], absint( '10' ), absint( '2' ) );

            /**
             *  Zip Code Collection
             *  -------------------
             */
            add_filter( 'sdweddingdirectory/venue-location/zip-code-collection', [ $this, 'zip_codes_collection' ], absint( '10' ) );

            /**
             *  Get Parent term ID
             *  ------------------
             */
            add_filter( 'sdweddingdirectory/term-parent', [ $this, 'term_parent' ], absint( '10' ), absint( '1' ) );

            /**
             *  Get Parent term IDs
             *  -------------------
             */
            add_filter( 'sdweddingdirectory/term-parent-list', [ $this, 'term_parent_list' ], absint( '10' ), absint( '1' ) );

            /**
             *  Get Term ID to child terms 
             *  --------------------------
             */
            add_filter( 'sdweddingdirectory/term-id/child-data', [ $this, 'term_child_data' ], absint( '10' ), absint( '1' ) );

            /**
             *  Get Term ID to child terms 
             *  --------------------------
             */
            add_filter( 'sdweddingdirectory/post/location', [ $this, 'post_location' ], absint( '10' ), absint( '1' ) );

            /**
             *  Get Term ID depth level
             *  -----------------------
             */
            add_filter( 'sdweddingdirectory/term/depth', [ $this, 'term_depth_level' ], absint( '10' ), absint( '1' ) );

            /**
             *  Get Term Name
             *  -------------
             */
            add_filter( 'sdweddingdirectory/term/name', [ $this, 'term_name' ], absint( '10' ), absint( '1' ) );

            /**
             *  Get Term Group Box Info
             *  -----------------------
             */
            add_filter( 'sdweddingdirectory/term-box-group', [ $this, 'term_data' ], absint( '10' ), absint( '1' ) );

            /**
             *  Post Taxonomy
             *  -------------
             */
            add_filter( 'sdweddingdirectory/post-tax', [ $this, 'post_taxonomy' ], absint( '10' ), absint( '1' ) );

            /**
             *  Filter Wise Get ACF Fields Data
             *  -------------------------------
             */
            add_filter( 'sdweddingdirectory/term/image', [ $this, 'term_image' ], absint( '10' ), absint( '1' ) );

            /**
             *  Filter Wise Get ACF Fields Data
             *  -------------------------------
             */
            add_filter( 'sdweddingdirectory/term/icon', [ $this, 'term_icon' ], absint( '10' ), absint( '1' ) );

            /**
             *  Filter Wise Get ACF Fields Data
             *  -------------------------------
             */
            add_filter( 'sdweddingdirectory/term/marker', [ $this, 'term_marker' ], absint( '10' ), absint( '1' ) );

            /**
             *  Term ID exists check with system
             *  --------------------------------
             */
            add_filter( 'sdweddingdirectory/term-id/exists', [ $this, 'this_term_id_exists_in_parent' ], absint( '10' ), absint( '1' ) );

            /**
             *  Term Name exists check with system
             *  ----------------------------------
             */
            add_filter( 'sdweddingdirectory/term-name/exists', [ $this, 'this_term_name_exists_in_parent' ], absint( '10' ), absint( '1' ) );

            /**
             *  Post - Term Object to Get Parent ID
             *  -----------------------------------
             */
            add_filter( 'sdweddingdirectory/post/term-parent', [ $this, 'post_parent_term' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Get Term Data
         *  -------------
         */
        public static function term_image( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'term_id'       =>      absint( '0' ),

                    'taxonomy'      =>      sanitize_key( 'venue-type' ),

                    'image_size'    =>      esc_attr( 'sdweddingdirectory_img_400x330' ),

                    'term_meta'     =>      esc_attr( 'term_image' ),

                    'default_img'   =>      parent:: placeholder( 'wishlist-box' ),

                ] ) );

                /**
                 *  Have Media ID ?
                 *  ---------------
                 */
                $media_id   =       sdwd_get_term_field( $term_meta, $term_id );

                /**
                 *  Media ID Empty!
                 *  ---------------
                 */
                if( parent:: _have_media( $media_id ) ){

                    return      apply_filters( 'sdweddingdirectory/media-data', [

                                    'media_id'      =>      absint( $media_id ),

                                    'image_size'    =>      esc_attr( $image_size )

                                ] );
                }

                /**
                 *  Default - Image
                 *  ---------------
                 */
                else{
                    
                    return  esc_url( $default_img );
                }
            }
        }

        /**
         *  Get Term Data
         *  -------------
         */
        public static function term_icon( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'term_id'           =>      absint( '0' ),

                    'taxonomy'          =>      sanitize_key( 'venue-type' ),

                    'term_meta'         =>      esc_attr( 'term_icon' ),

                    'default_icon'      =>      'fa fa-question'

                ] ) );

                /**
                 *  Make sure term id not emtpy!
                 *  ----------------------------
                 */
                if( empty( $term_id ) ){

                    return      $default_icon;
                }

                /**
                 *  Have Media ID ?
                 *  ---------------
                 */
                $term_icon          =       sdwd_get_term_field( $term_meta, $term_id );

                /**
                 *  Media ID Empty!
                 *  ---------------
                 */
                if( ! empty( $term_icon ) ){

                    return      $term_icon;
                }

                /**
                 *  Extract
                 *  -------
                 */
                $obj    =   get_term( $term_id, $taxonomy );

                /**
                 *  Make sure object not empty!
                 *  ---------------------------
                 */
                if( ! empty( $obj ) && $obj->parent != absint( '0' ) ){

                    /**
                     *  If Parent Term have icon ?
                     *  --------------------------
                     */
                    return      self:: term_icon( [

                                    'term_id'       =>      esc_attr( $obj->parent ),

                                    'taxonomy'      =>      esc_attr( $taxonomy )

                                ] ); ;
                }

                /**
                 *  Default - Image
                 *  ---------------
                 */
                else{
                    
                    return      $default_icon;
                }
            }
        }

        /**
         *  Get Term Data
         *  -------------
         */
        public static function term_marker( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'post_id'           =>      absint( '0' ),

                    'taxonomy'          =>      sanitize_key( 'venue-type' ),

                    'default_marker'    =>      esc_url( sdweddingdirectory_option( 'sdweddingdirectory_map_marker' ) ),

                ] ) );

                /**
                 *  Post ID not empty!
                 *  ------------------
                 */
                if( empty( $post_id ) ){

                    return          esc_url( $default_marker );
                }

                /**
                 *  Category ID
                 *  -----------
                 */
                $term_id                =       apply_filters( 'sdweddingdirectory/post/term-parent', [

                                                    'post_id'           =>      absint( $post_id ),

                                                    'taxonomy'          =>      esc_attr( $taxonomy ),

                                                ] );
                /**
                 *  Taxonomy Post ID
                 *  ----------------
                 */
                $taxonomy_post_id       =       esc_attr( $taxonomy . '_' . $term_id );

                /**
                 *  Have Media ID ?
                 *  ---------------
                 */
                $marker_condition       =       sdwd_get_term_field( 'marker_condition', $term_id );

                /**
                 *  Make sure : Marker condition is set
                 *  -----------------------------------
                 */
                if( $marker_condition   ==   true ){

                    /**
                     *  ACF : Custom Marker Image ID : [ venue_category_marker_image ] 
                     *  ----------------------------------------------------------------
                     */
                    $marker_icon       =       sdwd_get_term_field( 'marker_icon', $term_id );

                    /**
                     *  Have data ?
                     *  -----------
                     */
                    if( ! empty( $marker_icon ) ){

                        return      esc_url(  SDWEDDINGDIRECTORY_URL . '/config-file/icons-list/marker-icon/'. $marker_icon .'.svg'  );
                    }

                    /**
                     *  Default Marker
                     *  --------------
                     */
                    else{

                        return      $default_marker;
                    }
                }

                /**
                 *  Custom Image
                 *  ------------
                 */
                else{

                    /**
                     *  Marker Image
                     *  ------------
                     */
                    $marker_icon       =       sdwd_get_term_field( 'marker_image', $term_id );

                    /**
                     *  Have Marker Image
                     *  -----------------
                     */
                    if( parent:: _have_media( $marker_icon ) ){

                        return  esc_url(

                                    wp_get_attachment_image_src( 

                                        absint( $marker_icon ), esc_attr( 'full' )  

                                    )[ absint( '0' ) ]
                                );
                    }

                    /**
                     *  Default Marker
                     *  --------------
                     */
                    else{

                        return      $default_marker;
                    }
                }
            }
        }

        /**
         *  Get Enviroment
         *  --------------
         */
        public static function term_data( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'term_id'       =>      absint( '0' ),

                    'slug'          =>      '',

                    'taxonomy'      =>      esc_attr( 'venue-type' )

                ] ) );

                /**
                 *  Make sure term id and slug is not empty!
                 *  ----------------------------------------
                 */
                if( empty( $term_id ) || empty( $slug ) ){
                    
                    return      [];
                }

                /**
                 *  Get List
                 *  --------
                 */
                $list       =       sdwd_get_term_repeater( $slug, $term_id );

                /**
                 *  Have Data ?
                 *  -----------
                 */
                if( parent:: _is_array( $list ) ){

                    $first  =   reset( $list );

                    /**
                     *  Label/Value Structure
                     *  ---------------------
                     */
                    if( parent:: _is_array( $first ) && isset( $first['label'], $first['value'] ) ){

                        $options = [];

                        foreach( $list as $row ){

                            if( ! empty( $row['value'] ) ){

                                $options[ $row['value'] ] = ! empty( $row['label'] ) ? $row['label'] : $row['value'];
                            }
                        }

                        return  $options;
                    }

                    /**
                     *  Legacy Structure
                     *  ----------------
                     */
                    return      array_column( $list, $slug );
                }

                else{

                    return      [];
                }
            }
        }

        /**
         *  Term Name
         *  ---------
         */
        public static function term_name( $args = [] ){

            /**
             *  Make sure is Array
             *  ------------------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'term_id'       =>      absint( '0' ),

                    'taxonomy'      =>      esc_attr( 'venue-location' )

                ] ) );

                /**
                 *  Extract
                 *  -------
                 */
                $obj    =   get_term( $term_id, $taxonomy );

                /**
                 *  Make sure object not empty!
                 *  ---------------------------
                 */
                if( ! empty( $obj ) ){

                    return      esc_attr( $obj->name );
                }
            }
        }

        /**
         *  Depth Level of Taxonomy
         *  -----------------------
         */
        public static function term_depth_level( $args = [] ){

            /**
             *  Make sure is Array
             *  ------------------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'term_id'       =>      absint( '0' ),

                    'taxonomy'      =>      esc_attr( 'venue-location' )

                ] ) );

                /**
                 *  Get List
                 *  --------
                 */
                $ancestors      =   get_ancestors( $term_id, $taxonomy );

                /**
                 *  Is Array ?
                 *  ----------
                 */
                if( parent:: _is_array( $ancestors ) ){

                    return      count( $ancestors ) + 1;
                }
            }
        }

        /**
         *  1.2 Real Wedding - Location Get
         *  ------------------------------
         */
        public static function post_location( $args = [] ){

            /**
             *  Make sure post id not empty!
             *  ----------------------------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'post_id'               =>      absint( '0' ),

                    'taxonomy'              =>      esc_attr( 'venue-location' ),

                    'icon'                  =>      ' <i class="fa fa-map-marker me-1"></i> ',

                    'data'                  =>      [],

                    'before'                =>      '<span>',

                    'after'                 =>      '</span>',

                    'output'                =>      '',

                    'counter'               =>       absint( '1' ),

                    'random_id'             =>       esc_attr( parent:: _rand() ),

                    'parent_term'           =>      apply_filters( 'sdweddingdirectory/post-location/enable/first-level', false ),

                    'post_locations'        =>      [],

                    'button_class'          =>      'btn btn-link btn-link-primary text-decoration-none py-0 text-muted'

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return;
                }

                /**
                 *  Get Post Taxonomy Data
                 *  ----------------------
                 */
                $ids    =   wp_get_post_terms(

                                /**
                                 *  1. Post ID
                                 *  ----------
                                 */
                                absint( $post_id ),

                                /**
                                 *  2. Taxonomy Name
                                 *  ----------------
                                 */
                                sanitize_key( $taxonomy ),

                                /**
                                 *  3. Field
                                 *  --------
                                 */
                                array( 'fields' => 'ids', 'orderby' => 'parent' )
                            );

                /**
                 *  Have Terms ?
                 *  ------------
                 */
                if( parent:: _is_array( $ids ) ){

                    /**
                     *  Get Ids
                     *  -------
                     */
                    foreach( $ids as $key ){

                        /**
                         *  Have Child ?
                         *  ------------
                         */
                        $_have_child    =   apply_filters( 'sdweddingdirectory/term-id/child-data', [

                                                'term_id'       =>      absint( $key ),

                                                'taxonomy'      =>      $taxonomy

                                            ] );

                        /**
                         *  Testing
                         *  -------
                         */
                        $flag             =       false;

                        /**
                         *  No Child Found
                         *  --------------
                         */
                        if( ! parent:: _is_array( $_have_child ) ){

                            $flag       =   true;
                        }

                        /**
                         *  Still is array ok i will check array interset
                         *  ---------------------------------------------
                         */
                        elseif( parent:: _is_array( $_have_child ) ){

                            /**
                             *  Have Data
                             *  ---------
                             */
                            $data       =       array_intersect(  array_keys( $_have_child ), $ids  );

                            /**
                             *  Finally Find At least same value ?
                             *  ----------------------------------
                             */
                            if( !   parent:: _is_array( $data ) ){

                                $flag       =       true;
                            }
                        }

                        /**
                         *  Have Child ?
                         *  ------------
                         */
                        if( $flag ){

                            foreach( array_merge( [ $key ], get_ancestors( $key, $taxonomy ) ) as $_terms_id ){

                                /**
                                 *  Get Object
                                 *  ----------
                                 */
                                $obj            =   get_term_by( 'id', $_terms_id, $taxonomy );

                                /**
                                 *  Object Not Empty!
                                 *  -----------------
                                 */
                                if( ! empty( $obj ) ){

                                    /**
                                     *  Is Object ?
                                     *  -----------
                                     */
                                    if( $parent_term ){

                                        $post_locations[ $key ][]      =   apply_filters( 'sdweddingdirectory/term/name', [

                                                                                'term_id'       =>      $_terms_id,

                                                                                'taxonomy'      =>      $taxonomy

                                                                            ] );
                                    }

                                    /**
                                     *  Parent Object [ Country ] Not Added in Group
                                     *  ---------------------------------------------
                                     */
                                    elseif( ! empty( $obj->parent ) ){

                                        $post_locations[ $key ][]      =   apply_filters( 'sdweddingdirectory/term/name', [

                                                                                'term_id'       =>      $_terms_id,

                                                                                'taxonomy'      =>      $taxonomy

                                                                            ] );
                                    }
                                }
                            }
                        }
                    }
                }

                /**
                 *  Have Data ?
                 *  -----------
                 */
                if( parent:: _is_array( $post_locations ) ){

                    /**
                     *  Load all locations
                     *  ------------------
                     */
                    foreach( $post_locations as $key => $value ){

                        /**
                         *  Make sure it's first
                         *  --------------------
                         */
                        if( $counter == absint( '1' ) ){

                            /**
                             *  Make sure venue location have state / region / city
                             *  -----------------------------------------------------
                             */
                            if( parent:: _is_array( $value ) && count( $value ) >= absint( '2' ) ){

                                $output     .=      $before  .  $icon  .  implode( ', ', [ reset( $value ), end( $value ) ] );
                            }

                            /**
                             *  Load Selected Location Data
                             *  ---------------------------
                             */
                            else{

                                $output     .=      $before  .   $icon   .   implode( ', ', $value );
                            }

                            /**
                             *  Have more locations found ?
                             *  ---------------------------
                             */
                            if( count( $post_locations ) >= absint( '2' ) ){

                                $output    .=      

                                sprintf( '<button class="%4$s" 

                                            type="button" aria-expanded="false" aria-controls="%1$s" 

                                            data-bs-toggle="collapse" data-bs-target=".%1$s">

                                                <small class="fw-bold text-dark">%2$s+ %3$s</small>

                                            </button>',

                                            /**
                                             *  1. Random ID
                                             *  ------------
                                             */
                                            $random_id,

                                            /**
                                             *  2. How many location counter
                                             *  ----------------------------
                                             */
                                            absint( count( $post_locations ) - absint( '1' ) ),

                                            /**
                                             *  3. Translation Ready String
                                             *  ---------------------------
                                             */
                                            esc_attr__( 'More..', 'sdweddingdirectory' ),

                                            /**
                                             *  4. Button Class
                                             *  ---------------
                                             */
                                            $button_class
                                );
                            }

                            /**
                             *  Close Tag
                             *  ---------
                             */
                            $output     .=      $after;
                        }

                        /**
                         *  More location in collapse
                         *  -------------------------
                         */
                        else{

                            /**
                             *  Make sure venue location have state / region / city
                             *  -----------------------------------------------------
                             */
                            $output    .=   sprintf(   '<div class="collapse %1$s" id="%1$s">%2$s</div>',

                                                        /**
                                                         *  1. Div ID
                                                         *  ---------
                                                         */
                                                        $random_id,

                                                        /**
                                                         *  2. Data
                                                         *  -------
                                                         */
                                                        parent:: _is_array( $value ) && count( $value ) >= absint( '2' )

                                                        ?   $before  .  $icon  .  implode( ', ', [ reset( $value ), end( $value ) ] ) . $after

                                                        :   $before  .   $icon   .   implode( ', ', $value ) . $after
                                            );
                        }

                        /**
                         *  Counter ++
                         *  ----------
                         */
                        $counter++;
                    }
                }

                /**
                 *  Return
                 *  ------
                 */
                return      $output;
            }
        }

        /**
         *  Get Parent Object ID
         *  --------------------
         */
        public static function term_parent( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'term_id'       =>      absint( '0' ),

                    'taxonomy'      =>      esc_attr( 'venue-location' ),

                ] ) );

                /**
                 *  Make sure term id not empty
                 *  ---------------------------
                 */
                if( empty( $term_id ) ){

                    return      false;
                }

                /**
                 *  Create Static Variable
                 *  ----------------------
                 */
                static $parent_ids  =   '';

                if ( $term_id != 0 ) {

                    $category_parent    =  get_term( $term_id, $taxonomy );

                    $parent_ids         =   $category_parent->term_id;

                    self:: term_parent( [ 'term_id' => $category_parent->parent ] );
                }

                /**
                 *  Return Parent ID
                 *  ----------------
                 */
                return  $parent_ids;
            }
        }

        /**
         *  Get Parent Object ID
         *  --------------------
         */
        public static function term_parent_list( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'term_id'       =>      absint( '0' ),

                    'taxonomy'      =>      esc_attr( 'venue-location' ),

                ] ) );

                /**
                 *  Make sure term id not empty
                 *  ---------------------------
                 */
                if( empty( $term_id ) ){

                    return      false;
                }

                /**
                 *  Create Static Variable
                 *  ----------------------
                 */
                static $parent_ids  =   [];

                if ( $term_id != 0 ) {

                    $category_parent    =  get_term( $term_id, $taxonomy );

                    $parent_ids[]       =   $category_parent->term_id;

                    self:: term_parent( [ 'term_id' => $category_parent->parent ] );
                }

                /**
                 *  Return Parent ID
                 *  ----------------
                 */
                return  $parent_ids;
            }
        }

        /**
         *  Term Child Collection
         *  ---------------------
         */
        public static function term_child_data( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args )  ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'term_id'           =>      absint( '0' ),

                    'taxonomy'          =>      esc_attr( 'venue-location' ),

                    'collection'        =>      [],

                    '__return'          =>      esc_attr( 'name' ),

                    'all_child'         =>      false

                ] ) );

                /**
                 *  Make sure term id not empty
                 *  ---------------------------
                 */
                if( empty( $term_id ) ){

                    return      false;
                }

                /**
                 *  Object
                 *  ------
                 */
                if( $all_child ){

                    $child_term     =   get_term_children( $term_id, $taxonomy );
                }

                else{

                    $child_term     =   get_terms( $taxonomy, [ 'parent'  =>  $term_id ] );
                }

                /**
                 *  Have Data ?
                 *  -----------
                 */
                if( parent:: _is_array( $child_term ) ){

                    /**
                     *  Create Collection
                     *  -----------------
                     */
                    foreach( $child_term as $key => $value ){

                        /**
                         *  Get Object
                         *  ----------
                         */
                        $location_obj    =   get_term( $value, $taxonomy );

                        /**
                         *  Have required value set ?
                         *  -------------------------
                         */
                        if( $__return == esc_attr( 'group' ) ){

                            $collection[ absint( $location_obj->term_id ) ]   =   ( array ) $location_obj;
                        }

                        /**
                         *  Return Object
                         *  -------------
                         */
                        else{

                            $collection[ absint( $location_obj->term_id ) ]   =   $location_obj->$__return;
                        }
                    }
                }

                /**
                 *  Return Collection
                 *  -----------------
                 */
                return      $collection;
            }
        }

        /**
         *  Zip Code Collection
         *  -------------------
         */
        public static function zip_codes_collection( $args = [] ){

            /**
             *  Extract
             *  -------
             */
            extract( wp_parse_args( $args, [

                'taxonomy'      =>      esc_attr( 'venue-location' ),

                'hide_empty'    =>      true,

                'collection'    =>      []

            ] ) );

            /**
             *  Get Term List
             *  -------------
             */
            $terms          =   get_terms(  

                                     $taxonomy,

                                    [
                                        'hide_empty'    =>  $hide_empty,

                                        'orderby'       =>  esc_attr( 'name' ),

                                        'order'         =>  esc_attr( 'ASC' ),
                                    ] 
                                );
            /**
             *  Is Array ?
             *  ----------
             */
            if( parent:: _is_array( $terms ) ){

                foreach( $terms as $key ){

                    /**
                     *  Have Zip ?
                     *  ----------
                     */
                    $_have_zip      =   sdwd_get_term_field( 'zip_code', $key->term_id );

                    /**
                     *  Make sure zip not empty!
                     *  ------------------------
                     */
                    if( ! empty( $_have_zip ) ){

                        $collection[ absint( $key->term_id ) ]  =  $_have_zip;
                    }
                }
            }

            /**
             *  Return Collection
             *  -----------------
             */
            return      $collection;
        }

        /**
         *  Taxonomy Dropdown Menu
         *  ----------------------
         */
        public static function sdweddingdirectory_tax_parent( $taxonomy = '' ){

            /**
             *  Get Tax Name
             *  ------------
             */
            if( empty( $taxonomy ) ){

                return;
            }

            /**
             *  Collection
             *  ----------
             */
            $_collection        =       [];

            /**
             *  Have Taxonomy
             *  -------------
             */
            $_have_taxonomy     =       self:: get_taxonomy_parent( $taxonomy );

            /**
             *  Have Taxonomy ?
             *  ---------------
             */
            if( parent:: _is_array( $_have_taxonomy ) ){

                $_collection    =   $_have_taxonomy;
            }

            /**
             *  Have Data ?
             *  ===========
             */
            return          $_collection;
        }

        /**
         *  Taxonomy Dropdown Menu
         *  ----------------------
         */
        public static function sdweddingdirectory_tax_all_terms( $args = [], $taxonomy = '' ){

            /**
             *  Get Tax Name
             *  ------------
             */
            if( empty( $taxonomy ) ){

                return;
            }

            /**
             *  Handler
             *  -------
             */
            $terms      =       get_terms( $taxonomy, array(

                                    'hide_empty'        =>      false,

                                    'orderby'           =>      esc_attr( 'name' ),

                                    'order'             =>      esc_attr( 'ASC' ),

                                ) );
            /**
             *  Have list ?
             *  -----------
             */
            if( parent:: _is_array( $terms ) ){

                foreach( $terms as $key ){

                    $args[  absint( $key->term_id )  ]      =       esc_attr( $key->name );
                }
            }

            /**
             *  Return Args
             *  -----------
             */
            return      $args;
        }

        /**
         *      Get Texonomy
         */
        public static function get_taxonomy( $texonomy ){

            if( empty( $texonomy ) )
                return;

            $terms =  

            get_terms( $texonomy, array(

                'hide_empty'    => false,
                'orderby'       => 'name', 
                'order'         => 'ASC',
                'hierarchical'  => absint( '1' ),

            ) );

            $args = [];

            if( parent:: _is_array( $terms ) ){

                foreach( $terms as $key ){

                    $args[ absint( $key->term_id ) ] = esc_attr( $key->name );
                }
            }

            return $args;
        }

        public static function get_taxonomy_option( $tax, $depth ){

            $terms = self:: get_taxonomy_depth( $tax, $depth );

            $args = [];

            if( parent:: _is_array( $terms ) ){

                foreach( $terms as $key => $value ){

                    $args[ absint( $key ) ] =  $value->name;
                }
            }

            return $args;
        }

        public static function get_taxonomy_depth( $tax, $depth ){

            # This returns the whole taxonomy...
            $_whole_tax = get_terms( $tax, array( 'hide_empty' => false ) );

            $country = $state = $city = [];

            if( parent:: _is_array( $_whole_tax ) ){

                foreach ( $_whole_tax as $value ) {

                    if( $value->parent == absint( '0' ) ){

                        $country[ $value->term_id ] = $value;
                    }
                }
            }

            if( parent:: _is_array( $_whole_tax ) ){

                foreach ( $_whole_tax as $value ) {

                    if( array_key_exists( $value->parent, $country) ){

                        $state[ $value->term_id ] = $value;
                    }
                }
            }

            if( parent:: _is_array( $_whole_tax ) ){

                foreach ( $_whole_tax as $value ) {

                    if( array_key_exists( $value->parent, $state ) ){

                        $city[ $value->term_id ] = $value;
                    }
                }
            }

            if( $depth == absint( '1' ) ){

                return $country;

            }elseif( $depth == absint( '2' ) ){

                return $state;

            }elseif( $depth == absint( '3' ) ){

                return $city;
            }
        }

        public static function get_taxonomy_parent( $taxonomy, $hide_empty = false ){

            /**
             *  Make sure taxonomy not empty!
             *  -----------------------------
             */
            if( empty( $taxonomy ) ){

                return;
            }

            $args       =   [];

            $terms      =   get_terms( $taxonomy, [

                                'hide_empty'    =>      $hide_empty,

                                'orderby'       =>      'name',

                                'order'         =>      'ASC',

                                'parent'        =>      absint('0')

                            ] );

            /**
             *  Have Terms ?
             *  ------------
             */
            if( parent:: _is_array( $terms ) ){

                foreach( $terms as $key ){

                    $args[ absint( $key->term_id ) ]        =       esc_attr( $key->name );
                }
            }

            /**
             *  Return Args
             *  -----------
             */
            return      $args;
        }

        public static function get_taxonomy_child( $taxonomy, $child, $hide_empty = false ){

            /**
             *  Make sure taxonomy not empty!
             *  -----------------------------
             */
            if( empty( $taxonomy ) ){

                return;
            }

            $terms      =   get_terms( $taxonomy, [

                                'hide_empty'    =>      $hide_empty,

                                'orderby'       =>      'name',

                                'order'         =>      'ASC',

                                'parent'        =>      ''

                            ] );

            $args = $_other = $get_other = [];

            $_id = $_value = '';

            if( $child != '' ){

                if( parent:: _is_array( $terms ) ){

                    foreach( $terms as $key ){

                        if( $key->parent == $child ){

                            $args[ absint( $key->term_id ) ] = esc_attr( $key->name );
                        }
                    }
                }
            }

            return $args;
        }

        public static function get_taxonomy_link( $args, $default, $select = '' ){

            if( empty( $args ) ){

                return;
            }

            $_get_args = '';

            if( parent:: _is_array( $default ) ){

                foreach( $default as $key => $value ){

                    $_get_args .= sprintf( '<option value="%1$s">%2$s</option>', absint( '0' ), $value );
                }
            }

            if( parent:: _is_array( $args ) ){

                foreach( $args as $key => $value ){

                    $_get_args .=

                    sprintf( '<option value="%2$s" data-term-link="%4$s" %3$s>%1$s</option>', 
                        
                        $value,
                        
                        $key,
                        
                        ( $key == $select ) ? esc_attr( 'selected' ) : '',

                        /**
                         *  4. Link
                         *  -------
                         */
                        esc_url( get_term_link( $key ) )
                    );
                }
            }

            return $_get_args;
        }

        public static function get_venue_category_parent(){

            $terms =  get_terms( 'venue-type', array(

                        'hide_empty'    => true,
                        'orderby'       => 'name', 
                        'order'         => 'ASC',
                        'parent'        => ''
            ) );

            $args = $_other = $get_other = [];

            $_id = $_value = '';

            if( parent:: _is_array( $terms ) ){

                foreach( $terms as $key ){

                    if( $key->parent == absint( '0' ) ){

                        $args[ absint( $key->term_id ) ] = esc_attr( $key->name );
                    }
                }
            }

            return $args;
        }

        public static function get_taxonomy_child_args( $taxonomy, $taxonomy_id, $return_args ){

            $term_children  =   get_term_children( absint( $taxonomy_id ), $taxonomy );

            $counter = absint( '0' );

            if( parent:: _is_array( $term_children ) ){

                foreach( $term_children as $key => $value ){

                        $counter += get_term_by( 'id', $value, $taxonomy )->$return_args;
                }

                return absint( $counter );
            }

            return;
        }

        public static function get_parents_vendors(){

            return self:: get_taxonomy_parent( 'venue-type' );
        }

        public static function get_parents_locations(){

            return self:: get_taxonomy_parent( esc_attr( 'venue-location' ) );
        }


        public static function get_realwedding_category_list( $default = '', $label = '' ){

            return

            self:: create_select_option(

                self:: get_taxonomy( 'real-wedding-category' ), 

                ( ! empty( $label ) ) ? $label : '',

                $default
            );
        }

        public static function get_venue_category_list( $default = '', $label = '' ){

            return

            self:: create_select_option(

                self:: get_taxonomy( 'venue-type' ), 

                ( ! empty( $label ) ) ? $label : '',

                $default
            );
        }

        public static function get_location_list( $_taxonomy, $default = '', $_lable = '' ){

            return

            self:: create_select_option(

                self:: get_taxonomy_parent( $_taxonomy ), 

                ( ! empty( $label ) ) ? $label : '',

                $default
            );
        }

        public static function get_location_child_list( $_taxonomy, $default = '', $label = '' ){

            return 

            self:: create_select_option(

                  self:: get_taxonomy_child( $_taxonomy, $default ),

                  ( ! empty( $label ) ) ? $label : '',

                  $default
            ) ;
        }

        public static function sdweddingdirectory_get_term_name( $taxonomy, $args_id, $result ){

            /**
             *  Make sure taxonomy not empty!
             *  -----------------------------
             */
            if( empty( $taxonomy ) ){

                return;
            }

            $terms =  get_terms( $taxonomy, array(

                        'hide_empty'    => false,
                        'orderby'       => 'name', 
                        'order'         => 'ASC',
                        'hierarchical'  => absint( '1' ),
            ) );

            $return = '';

            if( parent:: _is_array( $terms ) ){

                foreach( $terms as $key ){

                    if( $key->term_id == $args_id ){

                        if( $result != '' ){

                            $return = $key->$result;

                        }else{

                            $return = $key->name;
                        }
                    }
                }
            }

            return $return;
        }


        /**
         * 
         *   Return Vendor Texonomy Categorie link
         * 
         */
        public static function get_taxonomy_singular_link( $_post_id, $_category ){

            $_args = wp_get_post_terms( absint( $_post_id ), $_category, array('fields' => 'ids') );

            if( parent:: _is_array( $_args ) ){

                return

                /**
                 *  @link  https://developer.wordpress.org/reference/functions/get_term_link/
                 */
                esc_url(  get_category_link(   $_args[ 0 ]   )  );
            }
        }

        public static function get_location_taxonomy( $post_id, $taxonomy ){

            if( empty( $post_id ) || empty( $taxonomy ) ){

                return;
            }

            $_tax_data = wp_get_post_terms(

                    /**
                     *  1. -- Post id
                     */
                    absint( $post_id ), 

                    /**
                     *  2. -- Get Taxonomy
                     */
                    sanitize_key($taxonomy),

                    /**
                     *  3. -- Filter Args
                     */
                    array( 'fields' => 'ids', 'orderby' => 'parent', 'order' => 'desc' ) 
            );

            if( parent:: _is_array( $_tax_data ) ){

                $_data = [];

                foreach( $_tax_data as $key => $value ){

                    $_data[] = self:: sdweddingdirectory_get_term_name( sanitize_key($taxonomy), $value, 'name' );
                }

                return $_data;
            }
        }

        /**
         *  Taxonomy Icon
         */
        public static function get_taxonomy_icon( $_post_id = 0, $_category = 'venue-type' ){

            if( empty( $_post_id ) ){

                return;
            }

            $_get_array = wp_get_post_terms(

                $_post_id, 

                $_category, 

                array( 'fields' => 'ids', 'orderby' => 'parent' ) 
            );

            if( parent:: _is_array( $_get_array ) ){

                return      apply_filters( 'sdweddingdirectory/term/icon', [  'term_id'   =>   absint( $_get_array[ absint( '0' ) ] )  ] );
            }
        }

        /**
         *  Get Taxonomy IDS
         *  ----------------
         */
        public static function post_taxonomy( $args = [] ) {

            /**
             *  Make sure have args
             *  -------------------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( wp_parse_args( $args, [

                    'post_id'           =>      absint( '0' ),

                    'taxonomy'          =>      sanitize_key( 'venue-type' ),

                    'parent_tax'        =>      true

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return;
                }

                /**
                 *  Collection
                 *  ----------
                 */
                $collection         =     wp_get_post_terms( absint( $post_id ), $taxonomy,

                                            /**
                                             *  Only Parent Term
                                             *  ----------------
                                             */
                                            $parent_tax

                                            ?   [ 'fields' => 'ids', 'parent' => 0, 'orderby' => 'parent' ] 

                                            :   [ 'fields' => 'ids', 'orderby' => 'parent' ]
                                        );

                /**
                 *  Have Collection
                 *  ---------------
                 */
                if( parent:: _is_array( $collection ) ){

                    /**
                     *  Return Only Parent Term
                     *  -----------------------
                     */
                    if( $parent_tax ){

                        return          $collection[ '0' ];
                    }

                    /**
                     *  Return Post Terms
                     *  -----------------
                     */
                    else{

                        return          $collection;
                    }
                }
            }
        }

        /**
         *  Term Id object parent child to confirm is exists ?
         *  --------------------------------------------------
         */
        public static function this_term_id_exists_in_parent( $args = [] ){

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

                    'term_id'       =>      absint( '0' ),

                    'taxonomy'      =>      sanitize_key( 'venue-location' ),

                    'parent_id'     =>      ''

                ] ) );

                /**
                 *  Make sure term id exists
                 *  ------------------------
                 */
                if( empty( $term_id ) ){

                    return      false;
                }

                /**
                 *  Term Object
                 *  -----------
                 */
                $term_obj         =       get_term( absint( $term_id ), esc_attr( $taxonomy ) );

                /**
                 *  Found the Object in DB ?
                 *  ------------------------
                 */
                if( ! empty( $term_obj ) ){

                    /**
                     *  Make sure term object have child
                     *  --------------------------------
                     */
                    if( $term_obj->parent != absint( '0' ) ){

                        /**
                         *  Parent Child Data
                         *  -----------------
                         */
                        $parent_child_list          =       apply_filters( 'sdweddingdirectory/term-id/child-data', [

                                                                'term_id'           =>      esc_attr( $term_obj->parent ),

                                                                'taxonomy'          =>      esc_attr( $taxonomy )

                                                            ] );
                        /**
                         *  Term Exists ?
                         *  -------------
                         */
                        $term_exists                =       term_exists( absint( $term_id ), esc_attr( $taxonomy ) );

                        /**
                         *  Have City Data Found ?
                         *  ----------------------
                         */
                        if( parent:: _is_array( $term_exists ) ){

                            /**
                             *  Parent ID
                             *  ---------
                             */
                            if( ! empty( $parent_id ) ){

                                /**
                                 *  Make sure city name found in region list
                                 *  ----------------------------------------
                                 */
                                if( array_key_exists( $term_id, $parent_child_list ) && $term_obj->parent == $parent_id ){

                                    return      absint( $term_id );
                                }
                            }

                            else{

                                /**
                                 *  Make sure city name found in region list
                                 *  ----------------------------------------
                                 */
                                if( array_key_exists( $term_id, $parent_child_list ) ){

                                    return      absint( $term_id );
                                }
                            }
                        }
                    }

                    /**
                     *  If this is parent term then please check this condition pass
                     *  ------------------------------------------------------------
                     */
                    else{

                        /**
                         *  Make sure term exists in system
                         *  -------------------------------
                         */
                        $term_exists       =       term_exists( absint( $term_id ), esc_attr( $taxonomy ) );

                        /**
                         *  Make sure term object parent not 0
                         *  ----------------------------------
                         */
                        if( parent:: _is_array( $term_exists ) && $term_obj->parent == absint( '0' ) ){

                            /**
                             *  Confirm both value is same
                             *  --------------------------
                             */
                            if( $term_exists[ 'term_id' ] == absint( $term_id ) ){

                                return      absint( $term_id );
                            }
                        }
                    }
                }

                /**
                 *  Return : False
                 *  --------------
                 */
                else{

                    return      false;
                }
            }
        }

        /**
         *  Term Id object parent child to confirm is exists ?
         *  --------------------------------------------------
         */
        public static function this_term_name_exists_in_parent( $args = [] ){

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

                    'term_name'         =>      '',

                    'taxonomy'          =>      sanitize_key( 'venue-location' ),

                    'parent_name'       =>      ''

                ] ) );

                /**
                 *  Make sure term id exists
                 *  ------------------------
                 */
                if( empty( $term_name ) ){

                    return      false;
                }

                /**
                 *  Term Object
                 *  -----------
                 */
                $term_obj         =       get_term_by( esc_attr( 'name' ), esc_attr( $term_name ), esc_attr( $taxonomy ) );

                /**
                 *  Have Parent Term ?
                 *  ------------------
                 */
                if( ! empty( $parent_name ) ){

                    $parent_obj       =       get_term_by( esc_attr( 'name' ), esc_attr( $parent_name ), esc_attr( $taxonomy ) );
                }

                /**
                 *  Found the Object in DB ?
                 *  ------------------------
                 */
                if( ! empty( $term_obj ) ){

                    /**
                     *  Make sure term have child
                     *  -------------------------
                     */
                    if( $term_obj->parent != absint( '0' ) ){

                        /**
                         *  Parent Child Data
                         *  -----------------
                         */
                        $parent_child_list          =       apply_filters( 'sdweddingdirectory/term-id/child-data', [

                                                                'term_id'           =>      esc_attr( $term_obj->parent ),

                                                                'taxonomy'          =>      esc_attr( $taxonomy )

                                                            ] );
                        /**
                         *  Term Exists ?
                         *  -------------
                         */
                        $term_exists                =       term_exists( esc_attr( $term_name ), esc_attr( $taxonomy ) );

                        /**
                         *  Have City Data Found ?
                         *  ----------------------
                         */
                        if( parent:: _is_array( $term_exists ) ){

                            /**
                             *  Parent ID
                             *  ---------
                             */
                            if( ! empty( $parent_name ) ){

                                /**
                                 *  Make sure city name found in region list
                                 *  ----------------------------------------
                                 */
                                if( array_key_exists( $term_obj->term_id, $parent_child_list ) && $term_obj->parent == $parent_obj->name ){

                                    return      absint( $term_obj->term_id );
                                }
                            }

                            else{

                                /**
                                 *  Make sure city name found in region list
                                 *  ----------------------------------------
                                 */
                                if( array_key_exists( $term_obj->term_id, $parent_child_list ) ){

                                    return      absint( $term_obj->term_id );
                                }
                            }
                        }
                    }

                    /**
                     *  This is parent term they have not any child
                     *  -------------------------------------------
                     */
                    else{

                        /**
                         *  Make sure term exists in system
                         *  -------------------------------
                         */
                        $term_exists                =       term_exists( esc_attr( $term_name ), esc_attr( $taxonomy ) );

                        /**
                         *  Make sure term object parent not 0
                         *  ----------------------------------
                         */
                        if( parent:: _is_array( $term_exists ) && $term_obj->parent == absint( '0' ) ){

                            /**
                             *  Confirm both value is same
                             *  --------------------------
                             */
                            if( $term_exists[ 'term_id' ] == absint( $term_obj->term_id ) ){

                                return      absint( $term_id );
                            }
                        }
                    }
                }

                /**
                 *  Return False
                 *  ------------
                 */
                else{

                    return      false;
                }
            }
        }

        /**
         *  Get Post ID Term Parent Object ID
         *  ---------------------------------
         */
        public static function post_parent_term( $args = [] ){

            /**
             *  Make sure have args ?
             *  ---------------------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( wp_parse_args( $args, [

                    'taxonomy'          =>      esc_attr( 'venue-type' ),

                    'post_id'           =>      absint( '0' ),

                    'collection'        =>      [],

                    'get_data'          =>      esc_attr( 'id' )

                ] ) );

                /**
                 *  Make sure post id not Empty!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return;
                }

                /**
                 *  Get Post Taxonomy Object
                 *  ------------------------
                 */
                $collection     =   wp_get_post_terms( $post_id, $taxonomy, [ 

                                        'fields'        =>      esc_attr( 'ids' ),

                                        'orderby'       =>      esc_attr( 'parent' ),

                                        'parent'        =>      absint( '0' )

                                    ] );
                /**
                 *  Have Data ?
                 *  -----------
                 */
                if( parent:: _is_array( $collection ) ){

                    /**
                     *  Found Term ID
                     *  -------------
                     */
                    $term_id                    =       $collection[ absint( '0' ) ];

                    /**
                     *  Term Exists ?
                     *  -------------
                     */
                    $term_exists                =       term_exists( absint( $term_id ), esc_attr( $taxonomy ) );

                    /**
                     *  Have City Data Found ?
                     *  ----------------------
                     */
                    if( parent:: _is_array( $term_exists ) ){

                        /**
                         *  Return Term ID
                         *  --------------
                         */
                        if(  $get_data  ==  esc_attr( 'id' )  ){

                            return          absint(  $term_id  );
                        }

                        /**
                         *  Return - Term Name
                         *  ------------------
                         */
                        elseif(  $get_data  ==  esc_attr( 'name' )  ){

                            return          apply_filters( 'sdweddingdirectory/term/name', [

                                                'taxonomy'      =>      $taxonomy,

                                                'term_id'       =>      absint(  $term_id  )

                                            ] );
                        }

                        /**
                         *  Return - Term Link
                         *  ------------------
                         */
                        elseif(  $get_data  ==  esc_attr( 'url' )  ||  $get_data  ==  esc_attr( 'link' )  ){

                            return          esc_url( get_term_link( absint(  $term_id  ),  $taxonomy  ) );
                        }
                    }
                }
            }
        }

        public static function create_select_option( $args, $default, $select = '' ){

            $_get_args = '';

            if( parent:: _is_array( $default ) ){

                foreach( $default as $key => $value ){

                    $_get_args .= sprintf( '<option value="%1$s">%2$s</option>', absint( '0' ), $value );
                }
            }

            if( parent:: _is_array( $args ) ){

                foreach( $args as $key => $value ){

                    /**
                     *  Selected Item is Array ?
                     *  ------------------------
                     */
                    if( parent:: _is_array( $select ) ){

                        $_get_args .=

                        sprintf( '<option value="%2$s" %3$s>%1$s</option>', 
                            
                            $value,
                            
                            $key,

                            array_key_exists( $key, $select )

                            ?   esc_attr( 'selected' ) 

                            :   ''
                        );

                    }else{

                        $_get_args .=

                        sprintf( '<option value="%2$s" %3$s>%1$s</option>', 
                            
                            $value,
                            
                            $key,
                            
                            ( $key == $select ) ? esc_attr( 'selected' ) : ''
                        );
                    }
                }
            }

            return $_get_args;
        }
    }

    /**
     *  Taxonomy Object
     *  ---------------
     */
    SDWeddingDirectory_Taxonomy:: get_instance();
}
