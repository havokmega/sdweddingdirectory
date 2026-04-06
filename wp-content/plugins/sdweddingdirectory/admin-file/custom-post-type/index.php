<?php
/**
 *  SDWeddingDirectory - Register Post and Taxonomy
 *  ---------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Register_Posts' ) && class_exists( 'SDWeddingDirectory_Admin_Settings' ) ) {

	/**
	 *  SDWeddingDirectory - Register Post and Taxonomy
	 *  ---------------------------------------
	 */
	class SDWeddingDirectory_Register_Posts extends SDWeddingDirectory_Admin_Settings {

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

	    public function __construct(){

            /**
             *  Load one by one shortcode file
             *  ------------------------------
             */
            foreach ( glob( plugin_dir_path( __FILE__ ) . '/*/index.php' ) as $file ) {
           
                require_once $file;
            }

            /**
             *  Register Taxonomy Filter
             *  ------------------------
             */
            add_filter( 'sdweddingdirectory/register-taxonomy', [ $this, 'register_taxonomy' ], absint( '10' ), absint( '1' ) );

            /**
             *  Term Meta - Header
             *  ------------------
             */
            add_filter( 'sdweddingdirectory/tax-meta/header-setting', [ $this, 'term_header_metabox' ], absint( '20' ), absint( '1' ) );

            add_filter( 'sdweddingdirectory/tax-meta/header-setting', [ $this, 'woocommerce_term_header_metabox' ], absint( '20' ), absint( '1' ) );

            /**
             *  Reorder Admin Menu
             *  ------------------
             */
            add_filter( 'custom_menu_order', '__return_true' );

            add_filter( 'menu_order', [ $this, 'reorder_admin_menu' ] );

            add_action( 'admin_menu', [ $this, 'register_admin_menu' ], 20 );

            /**
             *  Admin Menu Order List - Inside Divider
             *  --------------------------------------
             */
            add_filter( 'sdweddingdirectory/admin-menu/order', [ $this, 'divider' ], absint( '0' ), absint( '1' ) );

            add_filter( 'sdweddingdirectory/admin-menu/order', [ $this, 'divider' ], absint( '999' ), absint( '1' ) );

            /**
             *  Add Menu
             *  --------
             */
            add_filter( 'sdweddingdirectory/admin-menu/order', [ $this, 'menu_order_one' ], absint( '10' ), absint( '1' ) );
	    }

        /**
         *  Admin Menu Order #1
         *  --------------------
         */
        public static function divider( $args = [] ){

            $args[]         =       'sdweddingdirectory-separator';

            return                  $args;
        }

        /**
         *  Admin Menu Order #1
         *  --------------------
         */
        public static function menu_order_one( $args = [] ){

            $args[]         =       'sdweddingdirectory';

            $args[]         =       'edit.php?post_type=couple';

            $args[]         =       'edit.php?post_type=real-wedding';

            $args[]         =       'edit.php?post_type=website';

            $args[]         =       'edit.php?post_type=vendor';

            $args[]         =       'edit.php?post_type=venue';

            $args[]         =       'edit.php?post_type=pricing';

            $args[]         =       'edit.php?post_type=venue-request';

            $args[]         =       'edit.php?post_type=venue-review';

            $args[]         =       'edit.php?post_type=invoice';

            $args[]         =       'edit.php?post_type=claim-venue';

            $args[]         =       'edit.php?post_type=team';

            $args[]         =       'edit.php?post_type=testimonial';

            $args[]         =       'sdweddingdirectory-separator';

            return                  $args;
        }

        /**
         *  Enable Rest API ?
         *  -----------------
         */
        public static function rest_api_enable( $post_type = '' ){

            /**
             *  Post Type
             *  ---------
             */
            if( empty( $post_type ) ){

                return      [];
            }

            /**
             *  Return Rest API Key Args
             *  ------------------------
             */
            return      [   'show_in_rest'              =>  false,  //  block editor enable for this post type ?

                            'rest_base'                 =>  $post_type,

                            'rest_controller_class'     =>  'WP_REST_Posts_Controller',
                        ];
        }

        /**
         *  4. Register : Post Taxonomy
         *  ---------------------------
         */
        public static function register_taxonomy( $args = [] ) {

            /**
             *  Make sure it's array
             *  --------------------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( wp_parse_args( $args, [

                    'name'          =>      '',

                    'slug'          =>      '',

                    'post_type'     =>      '',

                    'rewrite'       =>      false,

                    'rest_api'      =>      false

                ] ) );

                /**
                 *  Slug and Name mandatory
                 *  -----------------------
                 */
                if( empty( $name ) || empty( $slug ) || empty( $post_type ) ){

                    return;
                }

                /**
                 *  Labels
                 *  ------
                 */
                $labels     =   array(

                    'name'                       =>   esc_attr( $name ),

                    'singular_name'              =>   esc_attr( $name ),

                    'menu_name'                  =>   esc_attr( $name ),

                    'all_items'                  =>   sprintf( esc_attr__( 'All %1$s', 'sdweddingdirectory' ), esc_attr( $name ) ),

                    'parent_item'                =>   sprintf( esc_attr__( 'Parent %1$s', 'sdweddingdirectory' ),  esc_attr( $name ) ),

                    'parent_item_colon'          =>   sprintf( esc_attr__( 'Parent %1$s:', 'sdweddingdirectory' ), esc_attr( $name ) ),

                    'new_item_name'              =>   sprintf( esc_attr__( 'New %1$s Name', 'sdweddingdirectory' ),    esc_attr( $name ) ),

                    'add_new_item'               =>   sprintf( esc_attr__( 'Add New %1$s', 'sdweddingdirectory' ), esc_attr( $name ) ),

                    'edit_item'                  =>   sprintf( esc_attr__( 'Edit %1$s', 'sdweddingdirectory' ),    esc_attr( $name ) ),

                    'update_item'                =>   sprintf( esc_attr__( 'Update %1$s', 'sdweddingdirectory' ),  esc_attr( $name ) ),

                    'view_item'                  =>   sprintf( esc_attr__( 'View %1$s', 'sdweddingdirectory' ),    esc_attr( $name ) ),

                    'separate_items_with_commas' =>   sprintf( esc_attr__( 'Separate %1$s with commas', 'sdweddingdirectory' ),    esc_attr( $name ) ),

                    'add_or_remove_items'        =>   sprintf( esc_attr__( 'Add or remove %1$s', 'sdweddingdirectory' ),   esc_attr( $name ) ),

                    'choose_from_most_used'      =>   sprintf( esc_attr__( 'Choose from the most used', 'sdweddingdirectory' ),    esc_attr( $name ) ),

                    'popular_items'              =>   sprintf( esc_attr__( 'Popular %1$s', 'sdweddingdirectory' ), esc_attr( $name ) ),

                    'search_items'               =>   sprintf( esc_attr__( 'Search %1$s', 'sdweddingdirectory' ),  esc_attr( $name ) ),

                    'not_found'                  =>   sprintf( esc_attr__( 'Not Found', 'sdweddingdirectory' ),    esc_attr( $name ) ),

                    'no_terms'                   =>   sprintf( esc_attr__( 'No %1$s', 'sdweddingdirectory' ),  esc_attr( $name ) ),

                    'items_list'                 =>   sprintf( esc_attr__( '%1$s list', 'sdweddingdirectory' ),    esc_attr( $name ) ),

                    'items_list_navigation'      =>   sprintf( esc_attr__( '%1$s list navigation', 'sdweddingdirectory' ), esc_attr( $name ) ),
                );

                /**
                 *  Register New Taxonomy
                 *  ---------------------
                 */
                register_taxonomy( sanitize_key( $slug ), array( $post_type ), array_merge(

                    [

                        'labels'                     =>  $labels,

                        'hierarchical'               =>  true,

                        'public'                     =>  true,

                        'show_ui'                    =>  true,

                        'show_admin_column'          =>  true,

                        'show_in_nav_menus'          =>  true,

                        'show_tagcloud'              =>  true,

                    ],

                    /**
                     *  Hirechical
                     *  ----------
                     */
                    $rewrite

                    ?   [   'args'          =>  [ 'orderby' => 'term_order' ],

                            'rewrite'       =>  [

                                                    'slug'              =>  sanitize_key( $slug ),

                                                    'with_front'        =>  true, 

                                                    'hierarchical'      =>  true 
                                                ]
                        ]

                    :   [],

                    /**
                     *  Rest API Key
                     *  ------------
                     */
                    $rest_api

                    ?   [   'show_in_rest'              =>      true,

                            'rest_base'                 =>      sanitize_key( $slug ),
                            
                            'rest_controller_class'     =>      'WP_REST_Terms_Controller',
                        ]

                    :   []

                ) );
            }
        }

        /**
         *  Term Meta - Header
         *  ------------------
         */
        public static function term_header_metabox( $args = [] ){

            return      array_merge( $args, [

                            [   
                                'taxonomy'      =>      esc_attr( 'category' ),

                                'rand_id'       =>      esc_attr( 'ndakmwu3ei' )
                            ]

                        ] );
        }

        /**
         *  Term Meta - Header
         *  ------------------
         */
        public static function woocommerce_term_header_metabox( $args = [] ){

            return      array_merge( $args, [

                            [   
                                'taxonomy'      =>      esc_attr( 'product_cat' ),

                                'rand_id'       =>      esc_attr( 'jwe2lqxadx' )
                            ]

                        ] );
        }

        /**
         *  Reorder the SDWeddingDirectory menu items in admin
         *  ------------------------------------------
         *  @ref - https://gist.github.com/RadGH/e8d0b3d2fbbbd35dacce507dde6dcd02
         *  ---------------------------------------------------------------------
         */
        public static function reorder_admin_menu( $menu ) {

            /**
             *  Extract Args
             *  ------------
             */
            extract( [

                /**
                 *  Target Menu
                 *  -----------
                 */
                'insert_after_item'      =>       apply_filters( 'sdweddingdirectory/admin-menu/order-after', 'edit-comments.php' ),

                /**
                 *  Add Menu
                 *  --------
                 */
                'rearrange_items'        =>       apply_filters(  'sdweddingdirectory/admin-menu/order', [] )

            ] );

            /**
             *  get the index of the item we'll be inserting after
             *  --------------------------------------------------
             */
            $insert_position        =       array_search( $insert_after_item, $menu );

            /**
             *  Found the Index ?
             *  -----------------
             */
            if ( ! $insert_position ){

                return      $menu;
            }

            /**
             *  Loop
             *  ----
             */
            foreach( $rearrange_items as $rearrange_k => $rearrange_value ) {

                /**
                 *  Find the Index of Value
                 *  -----------------------
                 */
                $item_key = array_search( $rearrange_value, $menu );

                /**
                 *  Have Key ?
                 *  ----------
                 */
                if ( ! $item_key ) {

                    /**
                     *  If an item to be rearranged does not exist, remove it so we do not add a broken item back in
                     *  --------------------------------------------------------------------------------------------
                     */
                    unset($rearrange_items[$rearrange_k]);
                }

                else{
                    
                    /**
                     *  We're going to move this item, so take it out of the original array
                     *  -------------------------------------------------------------------
                     */
                    unset($menu[$item_key]);
                }
            }

            /**
             *  We want to insert after the menu item location, so we add one.
             *  --------------------------------------------------------------
             */
            $insert_position++;

            /**
             *  Add our items after the index from before 
             *  -------------------------------------------
             *  Beautified code from a genius's comment on a Stack Overflow answer, http://stackoverflow.com/a/3354804/470480
             *  -------------------------------------------------------------------------------------------------------------
             */
            $menu   =   array_merge(

                            array_slice( $menu, 0, $insert_position, true ),   // Start with the first portion of the menu

                            $rearrange_items,                                  // Sandwich the new items in the middle

                            array_slice( $menu, $insert_position, null, true ) // Add the rest of the array
                        );

            /**
             *  Reorganize the array using numeric keys
             *  ---------------------------------------
             */
            return      array_values( $menu );
        }

        /**
         *  Menu
         *  ----
         */
        public function register_admin_menu() {

            global $menu;

            $menu[]     =   [ '', 'read', 'sdweddingdirectory-separator', '', 'wp-menu-separator sdweddingdirectory-separator' ];
        }
	}

	/**
	 *  SDWeddingDirectory - Register Post and Taxonomy
	 *  ---------------------------------------
	 */
    SDWeddingDirectory_Register_Posts::get_instance();	
}