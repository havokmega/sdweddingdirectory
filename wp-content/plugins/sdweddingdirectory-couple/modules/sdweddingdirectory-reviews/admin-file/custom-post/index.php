<?php
/**
 *  Register Post Type
 *  ------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Review_Custom_Post_Type' ) && class_exists( 'SDWeddingDirectory_Register_Posts' ) ){

    /**
     *  Register Post Type
     *  ------------------
     */
    class SDWeddingDirectory_Review_Custom_Post_Type extends SDWeddingDirectory_Register_Posts{

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
         *  Post Type
         *  ---------
         */
        public static function post_type(){

            return      esc_attr( 'venue-review' );
        }

        /**
         *  Menu Position
         *  -------------
         */
        public static function menu_position(){

            return      absint( '25' );
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  Register : Post
             *  ---------------
             */
            add_action( 'init', [ $this, 'create_post_type' ], absint( '10' ) );

            /**
             *  Column Name
             *  -----------
             */
            add_filter( 'manage_edit-venue-review_columns', [ $this, 'column_name' ], absint( '10' ), absint( '1' ) );

            /**
             *  Column Value
             *  ------------
             */
            add_action( 'manage_venue-review_posts_custom_column', [ $this, 'column_value' ], absint( '10' ), absint( '2' ) );

            /**
             *  Post Publish Action
             *  -------------------
             */
            // add_action( 'post_updated', [ $this, 'post_update_action' ], absint( '10' ), absint( '3' ) );
        }

        /**
         *  Register Post
         *  -------------
         */
        public static function create_post_type(){

            /**
             *  Label
             *  -----
             */
            $labels                         =       [

                'name'                      =>      _x( 'Reviews', 'Post Type General Name', 'sdweddingdirectory-reviews' ),

                'singular_name'             =>      _x( 'Review', 'Post Type Singular Name', 'sdweddingdirectory-reviews' ),

                'menu_name'                 =>      __( 'Review', 'sdweddingdirectory-reviews' ),

                'name_admin_bar'            =>      __( 'Review', 'sdweddingdirectory-reviews' ),

                'archives'                  =>      __( 'Item Review', 'sdweddingdirectory-reviews' ),

                'attributes'                =>      __( 'Item Review', 'sdweddingdirectory-reviews' ),

                'parent_item_colon'         =>      __( 'Parent Review:', 'sdweddingdirectory-reviews' ),

                'all_items'                 =>      __( 'All Reviews', 'sdweddingdirectory-reviews' ),

                'add_new_item'              =>      __( 'Add New Review', 'sdweddingdirectory-reviews' ),

                'add_new'                   =>      __( 'Add New Review', 'sdweddingdirectory-reviews' ),

                'new_item'                  =>      __( 'New Review', 'sdweddingdirectory-reviews' ),

                'edit_item'                 =>      __( 'Edit Review', 'sdweddingdirectory-reviews' ),

                'update_item'               =>      __( 'Update Review', 'sdweddingdirectory-reviews' ),

                'view_item'                 =>      __( 'View Review', 'sdweddingdirectory-reviews' ),

                'view_items'                =>      __( 'View Review', 'sdweddingdirectory-reviews' ),

                'search_items'              =>      __( 'Search Review', 'sdweddingdirectory-reviews' ),

                'not_found'                 =>      __( 'Not found', 'sdweddingdirectory-reviews' ),

                'not_found_in_trash'        =>      __( 'Not found in Trash', 'sdweddingdirectory-reviews' ),

                'featured_image'            =>      __( 'Featured Image', 'sdweddingdirectory-reviews' ),

                'set_featured_image'        =>      __( 'Set featured image', 'sdweddingdirectory-reviews' ),

                'remove_featured_image'     =>      __( 'Remove featured image', 'sdweddingdirectory-reviews' ),

                'use_featured_image'        =>      __( 'Use as featured image', 'sdweddingdirectory-reviews' ),

                'insert_into_item'          =>      __( 'Insert into item', 'sdweddingdirectory-reviews' ),

                'uploaded_to_this_item'     =>      __( 'Uploaded to this item', 'sdweddingdirectory-reviews' ),

                'items_list'                =>      __( 'Reviews list', 'sdweddingdirectory-reviews' ),

                'items_list_navigation'     =>      __( 'Reviews list navigation', 'sdweddingdirectory-reviews' ),

                'filter_items_list'         =>      __( 'Filter Reviews list', 'sdweddingdirectory-reviews' ),
            ];

            $slug                       =   self:: post_type();

            $slug_plural                =   $slug . 's';

            $rewrite                    =   [

                'slug'                  =>  $slug,

                'with_front'            =>  true,
            ];

            /**
             *  Args
             *  ----
             */
            $args                           =       [

                'label'                     =>      __( 'Review', 'sdweddingdirectory-reviews' ),

                'description'               =>      __( 'Venue Reviews', 'sdweddingdirectory-reviews' ),

                'labels'                    =>      $labels,

                'supports'                  =>      array( 'title' ),

                'taxonomies'                =>      [],

                'hierarchical'              =>      false,

                'public'                    =>      true,

                'show_ui'                   =>      true,

                'show_in_menu'              =>      true,

                'menu_position'             =>      self:: menu_position(),

                'menu_icon'                 =>      esc_attr( 'dashicons-star-filled' ),

                'show_in_admin_bar'         =>      true,

                'show_in_nav_menus'         =>      true,

                'can_export'                =>      true,

                'has_archive'               =>      true,

                'exclude_from_search'       =>      true,

                'publicly_queryable'        =>      true,

                'capability_type'           =>      esc_attr( 'post' ),

                'map_meta_cap'              =>      true,

                'query_var'                 =>      true,

                'rewrite'                   =>      $rewrite
            ];

            /**
             *  Register Post Type
             *  ------------------
             */
            register_post_type( self:: post_type(), array_merge( $args,

                /**
                 *  Enable Rest API
                 *  ---------------
                 */
                apply_filters( 'sdweddingdirectory/rest-api/' . self:: post_type(),  false  )

                ?       parent:: rest_api_enable(  self:: post_type()  )

                :       [],

                /**
                 *  Create Post Capacity ?
                 *  ----------------------
                 */
                apply_filters( 'sdweddingdirectory/post-cap/' . self:: post_type(), true  )

                ?   [   'capabilities'      =>     array( 'create_posts' => 'do_not_allow' )  ]

                :   []

            ) );
        }

        /**
         *  Column Name
         *  -----------
         */
        public static function column_name( $columns ){

            /**
             *  Couple Column
             *  -------------
             */
            $columns['couple']      =   esc_attr__( 'Couple Name', 'sdweddingdirectory-review' );

            /**
             *  Vendor Column
             *  -------------
             */
            $columns['vendor']      =   esc_attr__( 'Vendor Name', 'sdweddingdirectory-review' );

            /**
             *  Venue Column
             *  --------------
             */
            $columns['venue']     =   esc_attr__( 'Venue Name', 'sdweddingdirectory-review' );

            /**
             *  Column Data
             *  -----------
             */            
            return      $columns;
        }

        /**
         *  Column Value
         *  ------------
         */
        public static function column_value( $column, $post_id ) {

            /**
             *  Is Venue Column ?
             *  -------------------
             */
            if( $column == esc_attr( 'venue' ) ){

                /**
                 *  Get Venue Post ID
                 *  -------------------
                 */
                $post_id        =   get_post_meta( absint( $post_id ), sanitize_key( 'venue_id' ), true );

                /**
                 *  Post Info
                 *  ---------
                 */
                printf( '<span class="text-center">

                            <a href="%1$s" target="_blank">%2$s</a>

                        </span>',

                        /**
                         *  1. Get the Page link
                         *  --------------------
                         */
                        esc_url( get_the_permalink( $post_id ) ),

                        /**
                         *  2. Get the Title
                         *  ----------------
                         */
                        esc_attr( get_the_title( $post_id ) )
                );
            }

            /**
             *  Is Couple Column ?
             *  ------------------
             */
            elseif( $column == esc_attr( 'couple' ) ){

                /**
                 *  Post ID
                 *  -------
                 */
                $post_id        =       get_post_meta( absint( $post_id ), sanitize_key( 'couple_id' ), true );

                /**
                 *  Post ID
                 *  -------
                 */
                if( ! empty( $post_id ) ){

                    /**
                     *  Print Data
                     *  ----------
                     */
                    printf( '<span class="text-center">

                                <a href="%1$s" target="_blank">%2$s</a>

                            </span>',

                            /**
                             *  1. Get the Page link
                             *  --------------------
                             */
                            esc_url( get_the_permalink( $post_id ) ),

                            /**
                             *  2. Get the Title
                             *  ----------------
                             */
                            esc_attr( get_the_title( $post_id ) )
                    );
                }
            }

            /**
             *  Is Vendor Column ?
             *  ------------------
             */
            elseif( $column == esc_attr( 'vendor' ) ){

                /**
                 *  Post ID
                 *  -------
                 */
                $post_id        =       get_post_meta( absint( $post_id ), sanitize_key( 'vendor_id' ), true );

                /**
                 *  Post ID
                 *  -------
                 */
                if( ! empty( $post_id ) ){

                    /**
                     *  Print Data
                     *  ----------
                     */
                    printf( '<span class="text-center">

                                <a href="%1$s" target="_blank">%2$s</a>

                            </span>',

                            /**
                             *  1. Get the Page link
                             *  --------------------
                             */
                            esc_url( get_the_permalink( $post_id ) ),

                            /**
                             *  2. Get the Title
                             *  ----------------
                             */
                            esc_attr( get_the_title( $post_id ) )
                    );
                }
            }
        }

        /**
         *  Rating Publish Hook
         *  -------------------
         */
        public static function post_update_action( $post_ID, $post_after, $post_before ){

            /**
             *  Is Post Correct ?
             *  -----------------
             */
            if( $post_after->post_type == esc_attr( self:: post_type() ) && $post_after->post_status == esc_attr( 'publish' ) 

                && $post_before->post_status !== esc_attr( 'publish' ) && $post_ID !== '' && $post_ID !== absint( '0' ) && isset( $post_ID ) ){

                if( class_exists( 'SDWeddingDirectory_Email' ) && false ){

                    global $post, $wp_query;

                    $_vendor_id = get_post_meta( $post_ID, 'vendor_id', true );

                    $_couple_id = get_post_meta( $post_ID, 'couple_id', true );

                    /**
                     *  Vendor Review Publish Email
                     *  ---------------------------
                     */
                    SDWeddingDirectory_Email:: vendor_received_review( array(

                        'vendor_username'           =>  sanitize_user( get_the_title( absint( $_vendor_id ) ) ),

                        'couple_username'           =>  sanitize_user( get_the_title( absint( $_couple_id ) ) ),

                        'vendor_review_dashboard'   =>  apply_filters( 'sdweddingdirectory/vendor-menu/page-link', esc_attr( 'vendor-reviews' ) ),

                        'email_id'                  =>  sanitize_email( get_post_meta( $_vendor_id, 'user_email', true ) ),

                    ) );
                }
            }
        }
    }

    /**
     *  SDWeddingDirectory - Register Post
     *  --------------------------
     */
    SDWeddingDirectory_Review_Custom_Post_Type::get_instance();
}