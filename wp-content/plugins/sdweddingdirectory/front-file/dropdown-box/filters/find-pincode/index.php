<?php
/**
 *  -------------------------------
 *  SDWeddingDirectory - Dropdown - Filters
 *  -------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Dropdown_Find_PinCode_Filters' ) && class_exists( 'SDWeddingDirectory_Dropdown_Filters' ) ){

    /**
     *  -------------------------------
     *  SDWeddingDirectory - Dropdown - Filters
     *  -------------------------------
     */
    class SDWeddingDirectory_Dropdown_Find_PinCode_Filters extends SDWeddingDirectory_Dropdown_Filters {

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
             *  Find Location with Pincode
             *  --------------------------
             */
            add_filter( 'sdweddingdirectory/find-location/pincode', [ $this, 'find_zip_code_wise_venue' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Find Zip Code Wise Venue
         *  --------------------------
         */
        public static function find_zip_code_wise_venue( $args = [] ){

            /**
             *  Extract Args
             *  ------------
             */
            extract( wp_parse_args( $args, [

                'input_data'        =>      esc_attr( '0' ),

                'category_id'       =>      absint( '0' ),

                'post_type'         =>      esc_attr( 'venue' ),

                'handler'           =>      '',

                'meta_query'        =>      [],

                'tax_query'         =>      [],

                'post_break'        =>      absint( '100' )

            ] ) );

            /**
             *  Taxonomy
             *  --------
             */
            $taxonomy               =       esc_attr( $post_type . '-location' );

            /**
             *  Find Zip Code
             *  -------------
             */
            if( ! empty( $input_data ) ){

                /**
                 *  Get Venue Category ID
                 *  -----------------------
                 */
                if( ! empty( $category_id ) ){

                    $tax_query[]    =   array(

                        'taxonomy'  =>  esc_attr( $post_type . '-category' ),

                        'terms'     =>  absint( $category_id )
                    );
                }

                /**
                 *  Venue Min & Max Price Filter
                 *  ------------------------------
                 */
                if( ! empty( $input_data ) ){

                    /**
                     *  Venue Min Price Filter
                     *  ------------------------
                     */
                    $meta_query[]   =   array(

                        'key'       =>  esc_attr( 'venue_pincode' ),

                        'type'      =>  esc_attr( 'numeric' ),

                        'compare'   =>  esc_attr( 'LIKE' ),

                        'value'     =>  esc_attr( $input_data )
                    );
                }

                /**
                 *  Find Venue Query
                 *  ------------------
                 */
                $args           =   array_merge(

                                        /**
                                         *  Default args
                                         *  ------------
                                         */
                                        array(

                                            'post_type'         =>  esc_attr( $post_type ),

                                            'post_status'       =>  esc_attr( 'publish' ),

                                            'posts_per_page'    =>  -1,
                                        ),

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
                 *  WordPress to Find Query
                 *  -----------------------
                 */
                $collection             =   new WP_Query( $args );

                /**
                 *  Get Post IDs
                 *  ------------
                 */
                $_post_id_collaction    =   wp_list_pluck( $collection->posts, 'ID' );

                /**
                 *  WordPress to Find Query
                 *  -----------------------
                 */
                $item                   =   new WP_Query( array_merge(

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
                                                    'post__in'          =>  $_post_id_collaction,

                                                    'orderby'           =>  esc_attr( 'post__in' ),

                                                    'posts_per_page'    =>  -1,
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
                 *  Have Post ?
                 *  -----------
                 */
                if( $total_element >= absint( '1' ) ){

                    /**
                     *  IDs
                     *  ---
                     */
                    $_get_posts_ids     =   wp_list_pluck( $item->posts, 'ID' );

                    /**
                     *  Taxonomy Collection
                     *  -------------------
                     */
                    $_tax_collection    =   [];

                    /**
                     *  Have Collection of Post IDs ?
                     *  -----------------------------
                     */
                    foreach( $_get_posts_ids as $key => $value ){

                        /**
                         *  City ID
                         *  -------
                         */
                        $location_data  =   wp_get_post_terms(

                                                $value, 

                                                esc_attr( $taxonomy ),

                                                [ 'fields' => 'ids', 'childless' => true, 'orderby' => 'parent' ]
                                            );

                        /**
                         *  Make sure object not empty!
                         *  ---------------------------
                         */
                        if( parent:: _is_array( $location_data ) ){

                            /**
                             *  Collection of ids
                             *  -----------------
                             */
                            $_tax_collection[]      =   end( $location_data );
                        }
                    }

                    /**
                     *  Have Unique Collection of Taxonomy
                     *  ----------------------------------
                     */
                    if( parent:: _is_array( $_tax_collection ) ){

                        /**
                         *  In Loop
                         *  -------
                         */
                        foreach( array_unique( $_tax_collection ) as $key => $value ){

                            /**
                             *  Category Object
                             *  ---------------
                             */
                            $child_obj          =       get_term( $value, esc_attr( $taxonomy ) );

                            $city_id            =       absint( $child_obj->term_id );

                            $city_name          =       esc_attr( $child_obj->name );

                            /**
                             *  Make sure city id have parent id
                             *  --------------------------------
                             */
                            if( $child_obj->parent != absint( '0' ) ){

                                $parent_obj         =       get_term( $child_obj->parent, esc_attr( $taxonomy ) );

                                $state_id           =       absint( $parent_obj->term_id );

                                $state_name         =       esc_attr( $parent_obj->name );
                            }

                            else{

                                $state_id           =       '';

                                $state_name         =       '';
                            }

                            /**
                             *  Handler
                             *  -------
                             */
                            $handler   .=

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

                                            'term_id'       =>      $city_id,

                                            'taxonomy'      =>      sanitize_key( $taxonomy ),

                                        ] ),

                                        /**
                                         *  3. Full String
                                         *  --------------
                                         */
                                        esc_attr( $city_name ),

                                        /**
                                         *  4. State String Find
                                         *  --------------------
                                         */
                                        esc_attr( $city_name ),

                                        /**
                                         *  5. Translation Ready String
                                         *  ---------------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/term-id/area-name', [

                                            'term_id'       =>      $city_id,

                                            'taxonomy'      =>      $taxonomy

                                        ] ),

                                        /**
                                         *  6. Country String Find
                                         *  ----------------------
                                         */
                                        esc_attr( $state_name )
                            );

                            /**
                             *  Counter
                             *  -------
                             */
                            $post_break--;

                            /**
                             *  Checking Counter 
                             *  ----------------
                             */
                            if( $post_break == absint( '0' ) ){

                                break;
                            }
                        }
                    }
                }
            }

            /**
             *  Return Zip Code Post
             *  --------------------
             */
            return      $handler;
        }
    }

    /**
     *  -------------------------------
     *  SDWeddingDirectory - Dropdown - Filters
     *  -------------------------------
     */
    SDWeddingDirectory_Dropdown_Find_PinCode_Filters::get_instance();
}