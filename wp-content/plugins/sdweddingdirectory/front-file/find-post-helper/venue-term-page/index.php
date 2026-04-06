<?php
/**
 *  SDWeddingDirectory - Venue Term Page
 *  ------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Term_Page' ) && class_exists( 'SDWeddingDirectory_Find_Post_Helper' ) ){

    /**
     *  SDWeddingDirectory - Venue Term Page
     *  -----------------------------
     */
    class SDWeddingDirectory_Venue_Term_Page extends SDWeddingDirectory_Find_Post_Helper{

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
             *  1. Load Script
             *  --------------
             */
            add_action( 'wp_enqueue_scripts', [ $this, 'sdweddingdirectory_script' ] );

            /**
             *  2. Load Venue - Term Page
             *  ---------------------------
             */
            add_action( 'sdweddingdirectory/term-page/venue-location', [ $this, 'tax_page' ], absint( '10' ), absint( '1' ) );

            add_action( 'sdweddingdirectory/term-page/venue-type', [ $this, 'tax_page' ], absint( '10' ), absint( '1' ) );

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
                        esc_attr( 'sdweddingdirectory_venue_term_page' )
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
            if( is_tax( [ 'venue-type', 'venue-location' ] ) ){

                /**
                 *  Global Var
                 *  ----------
                 */
                global $post, $wp_query;

                /**
                 *  Extract
                 *  -------
                 */
                extract( (array) get_queried_object() );

                /**
                 *  Have Child Taxonomy ?
                 *  ---------------------
                 */
                $have_child     =   get_term_children( $term_id, $taxonomy );

                $child_terms    =   get_terms( $taxonomy, [

                                        'hide_empty'    =>  false,

                                        'orderby'       =>  'name',

                                        'order'         =>  'ASC',

                                        'parent'        =>  ''

                                    ] );

                /**
                 *   Have Term ?
                 *   -----------
                 */
                $child_term_ids   =   [];

                if( SDWeddingDirectory_Loader:: _is_array( $child_terms ) ){

                    foreach( $child_terms as $key ){

                        if( $key->parent == $term_id && $key->count >= absint( '1' ) ){

                            $child_term_ids[] =  absint( $key->term_id );
                        }
                    }
                }

                /**
                 *  No child term found
                 *  -------------------
                 */
                if( ! parent:: _is_array( $child_term_ids ) ){

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
                        array( 'jquery', 'toastr', 'pagination' ),

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

                    /**
                     *  Load Library Condition
                     *  ----------------------
                     */
                    add_filter( 'sdweddingdirectory/enable-script/review', function( $args = [] ){

                        return  array_merge( $args, [ 'venue-location'   =>  true ] );
                        
                    } );

                    /**
                     *  Load Library Condition
                     *  ----------------------
                     */
                    add_filter( 'sdweddingdirectory/enable-script/pagination', function( $args = [] ){

                        return  array_merge( $args, [ 'venue-location'   =>  true ] );
                        
                    } );

                    /**
                     *  Load Library Condition
                     *  ----------------------
                     */
                    add_filter( 'sdweddingdirectory/enable-script/map', function( $args = [] ){

                        return  array_merge( $args, [ 'venue-location'   =>  true ] );
                        
                    } );

                    /**
                     *  Load Library Condition
                     *  ----------------------
                     */
                    add_filter( 'sdweddingdirectory/enable-script/map-marker', function( $args = [] ){

                        return  array_merge( $args, [ 'venue-location'   =>  true ] );
                        
                    } );
                }
            }
        }

        /**
         *  AJAX : Location Tax Data
         *  ------------------------
         */
        public static function sdweddingdirectory_venue_term_page(){

            /**
             *  Get Result
             *  ----------
             */
            die(  json_encode(  self:: display_result( [

                'term_id'       =>      isset( $_POST[ 'term_id' ] ) && $_POST[ 'term_id' ] != '' 

                                        ?   absint( $_POST[ 'term_id' ] )

                                        :   absint( '0' ),

                'paged'         =>      isset( $_POST[ 'paged' ] ) && $_POST[ 'paged' ] != '' 

                                        ?   absint( $_POST[ 'paged' ] )

                                        :   absint( '0' ),

                'layout'        =>      isset( $_POST[ 'layout' ] ) && $_POST[ 'layout' ] != '' 

                                        ?   absint( $_POST[ 'layout' ] )

                                        :   absint( '0' )

            ] )  )  );
        }

        /**
         *  2. Load Venue With Parameter
         *  ------------------------------
         */
        public static function tax_page( $args = [] ){

            /**
             *  Is Array ?
             *  ----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, array(

                    'paged'             =>      isset( $_GET[ 'paged' ] )  &&   ! empty( $_GET[ 'paged' ] )

                                                ?   absint( $_GET[ 'paged' ] )

                                                :   absint( '1' ),

                    'layout'            =>      apply_filters( 'sdweddingdirectory/venue-taxonomy/layout', absint( '0' ) ),

                    'map_enable'        =>      parent:: sdweddingdirectory_have_map(),

                    'content'           =>      ''

                ) ) );

                /**
                 *  Venue Container
                 *  -----------------
                 */
                $result_data        =       self:: display_result( [

                                                'term_id'       =>      absint( $term_id ),

                                                'paged'         =>      absint( $paged ),

                                                'layout'        =>      absint( $layout ),

                                                'map_enable'    =>      $map_enable

                                            ] );

                /**
                 *  Div Start
                 *  ---------
                 */
                ?><div class="row" id="sdweddingdirectory_find_venue_section"><?php

                    /**
                     *   Load Div Structure
                     *   ------------------
                     */
                    printf(     '<div class="col-12">

                                    <div id="sdweddingdirectory_venue_tax_page_sorting">%1$s</div>

                                    <div class="d-none">%2$s</div>
                                    
                                </div>', 

                                /**
                                 *  Venue Layout
                                 *  --------------
                                 */
                                parent:: sdweddingdirectory_venue_layout( [

                                    'layout'            =>      absint( $layout ),

                                    'found_result'      =>      absint( $result_data[ 'found_result' ] )

                                ] ),

                                /**
                                 *  Hidden Input
                                 *  ------------
                                 */
                                parent:: venue_filter_hidden_input( [

                                    'echo'          =>      false

                                ] )
                    );

                    /**
                     *  Get Content
                     *  -----------
                     */
                    $content    =   sprintf(   '<div id="venue_search_result" class="row">%1$s</div>

                                                <div id="venue_have_pagination" class="row text-center">%2$s</div>',

                                                /**
                                                 *  1. Get Content
                                                 *  --------------
                                                 */
                                                !   empty( $result_data[ 'venue_html_data' ] )

                                                ?   $result_data[ 'venue_html_data' ]

                                                :   '',

                                                /**
                                                 *  2. Pagination
                                                 *  -------------
                                                 */
                                                !   empty( $result_data[ 'pagination' ] )

                                                ?   $result_data[ 'pagination' ]

                                                :   ''
                                    );

                    
                    /**
                     *  Enable Map ?
                     *  ------------
                     */
                    if( $map_enable ){

                        /**
                         *  Map Enable
                         *  ----------
                         */
                        printf(    '<div class="col-lg-8 col-md-12 col-sm-12 col-12">%1$s</div>

                                    <div class="col-lg-4 col-md-12 col-12" id="map_handler" data-map-id="%2$s" data-map-class="%3$s">
                                        
                                        <div id="%2$s" class="%3$s"></div>

                                    </div>', 

                                /**
                                 *  1. Content
                                 *  ----------
                                 */
                                $content,

                                /**
                                 *  2. Map Handler ID
                                 *  -----------------
                                 */
                                esc_attr( 'sdweddingdirectory_venue_tax_map' ),

                                /**
                                 *  3. Map Class
                                 *  ------------
                                 */
                                sanitize_html_class( 'venue_location_tax_map' )
                        );
                    }

                    /**
                     *  Without Map
                     *  -----------
                     */
                    else{

                        /**
                         *  Load Content
                         *  ------------
                         */
                        printf( '<div class="col-12">%1$s</div>', $content );

                    }

                ?></div><?php
            }
        }

        /**
         *  Find Venue Taxonomy Result Page
         *  ---------------------------------
         */
        public static function display_result( $args = [] ){

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

                    'tax_query'                 =>      [],

                    'paged'                     =>      absint( '1' ),

                    'per_page'                  =>      apply_filters( 'sdweddingdirectory/venue-taxonomy/post-per-page', absint( '9' ) ),

                    'venue_tab_one'           =>      '',

                    'venue_tab_two'           =>      '',

                    'layout'                    =>      absint( '0' ),

                    'map_enable'                =>      parent:: sdweddingdirectory_have_map(),

                    'grid_column'               =>      'row row-cols-sm-4 row-cols-1',

                    'handler'                   =>      [],

                    'orderby'                   =>      apply_filters( 'sdweddingdirectory/venue-taxonomy/orderby', esc_attr( 'post_date' ) ),

                ] ) );

                /**
                 *  If Map Disable Then Grid Layout Column
                 *  --------------------------------------
                 */
                if( $map_enable ){

                    $grid_column        =       'row row-cols-sm-2 row-cols-1';
                }

                /**
                 *  Have Term
                 *  ---------
                 */
                if( ! empty( $term_id ) ){

                    /**
                     *  Collect Tax
                     *  -----------
                     */
                    $tax_query[]    =   array(

                                            'taxonomy'      =>      apply_filters( 'sdweddingdirectory/term-id/tax', absint( $term_id ) ),

                                            'terms'         =>      absint( $term_id ),
                                        );
                }

                /**
                 *  Find Venue Query
                 *  ------------------
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

                                                'orderby'               =>      esc_attr( $orderby )
                                            ),

                                            /**
                                             *  If Have Taxonomy Query ?
                                             *  ------------------------
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
                $collection                 =       new WP_Query( $args );

                /**
                 *  Get Post IDs
                 *  ------------
                 */
                $post_id_collaction         =       wp_list_pluck( $collection->posts, 'ID' );

                /**
                 *  Make sure have collection
                 *  -------------------------
                 */
                if( parent:: _is_array( $post_id_collaction ) ){

                    /**
                     *  Sorty By Collection
                     *  -------------------
                     */
                    $post_id_collaction    =       apply_filters( 'sdweddingdirectory/venue/badge-filter', $post_id_collaction );
                }

                /**
                 *  Empty!
                 *  ------
                 */
                else{

                    $post_id_collaction    =       [];
                }

                /**
                 *  WordPress to Find Query
                 *  -----------------------
                 */
                $item               =   new WP_Query( array_merge(

                                            /**
                                             *  Prev Query
                                             *  ----------
                                             */
                                            $args,

                                            /**
                                             *  New args
                                             *  --------
                                             */
                                            array(

                                                /**
                                                 *  Collected the IDs filter via badge
                                                 *  ----------------------------------
                                                 */
                                                'post__in'          =>  $post_id_collaction,

                                                'orderby'           =>  esc_attr( 'post__in' ),

                                                'posts_per_page'    =>  absint( $per_page ),

                                                'paged'             =>  absint( $paged ),
                                            ),

                                        ) );
                
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
                     *  Post ID Collection
                     *  ------------------
                     */
                    $posts_ids     =   wp_list_pluck( $item->posts, 'ID' );

                    /**
                     *  Get Venue Post Ids
                     *  --------------------
                     */
                    if( parent:: _is_array( $posts_ids ) ){

                        /**
                         *  Get Venue
                         *  -----------
                         */
                        foreach ( $posts_ids as $key => $value ){

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

                        /**
                         *  Return Data
                         *  -----------
                         */
                        $content =    sprintf(   '<div class="tab-content" id="sdweddingdirectory-find-venue-tab-content">

                                                    <div class="tab-pane fade %1$s" id="%2$s" role="tabpanel" aria-labelledby="%2$s-tab">

                                                        <div class="%7$s">%3$s</div>

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
                                                $venue_tab_two,

                                                /**
                                                 *  7. Venue Post Column
                                                 *  ----------------------
                                                 */
                                                $grid_column
                                    );

                        /** 
                         *  Have Venue Data ?
                         *  -------------------
                         */
                        if( ! empty( $content ) ){

                            /**
                             *  Return Result Data
                             *  ------------------
                             */
                            $handler    =   array(

                                                /**
                                                 *  1. Venue Data With Pagination
                                                 *  -------------------------------
                                                 */
                                                'venue_html_data'     =>  $content,

                                                /**
                                                 *  2. Pagination
                                                 *  -------------
                                                 */
                                                'pagination'            =>  parent:: create_pagination(

                                                                                absint( $total_element ),

                                                                                absint( $per_page ),

                                                                                absint( $paged )
                                                                            ),
                                                /**
                                                 *  3. Total Post Found
                                                 *  -------------------
                                                 */
                                                'found_result'          =>  parent:: _is_array( $posts_ids )

                                                                            ?   absint( count( $posts_ids ) )

                                                                            :   absint( '0' )
                                            );
                        }
                    }
                }
            }

            /**
             *  Have Data ?
             *  ----------
             */
            if( parent:: _is_array( $handler ) ){

                return      $handler;
            }

            /**
             *  No Data Found !
             *  --------------
             */
            else{

                return      parent:: venue_not_found();
            }
        }
    }

    /**
     *  SDWeddingDirectory - Venue Term Page
     *  ------------------------------
     */
    SDWeddingDirectory_Venue_Term_Page::get_instance();
}