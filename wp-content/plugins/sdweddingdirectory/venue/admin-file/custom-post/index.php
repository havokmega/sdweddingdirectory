<?php
/**
 *  Register Post and Taxonomy
 *  --------------------------
 *  @helpful article for depth taxonomy show with hirechical
 *  --------------------------------------------------------
 *  @link - https://wordpress.stackexchange.com/questions/58658/rewrite-rules-hierarchical
 *  --------------------------------------------------------------------------------------
 *  SDWeddingDirectory - Register Post
 *  --------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Register_Venue_Post' ) && class_exists( 'SDWeddingDirectory_Register_Posts' ) ) {

    /**
     *  SDWeddingDirectory - Register Post
     *  --------------------------
     */
	class SDWeddingDirectory_Register_Venue_Post extends SDWeddingDirectory_Register_Posts {

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

            /**
             *  Slug
             *  ----
             */
            return      esc_attr( 'venue' );
        }

        /**
         *  Menu Position
         *  -------------
         */
        public static function menu_position(){

        	return  	absint( '25' );
        }

        /**
         *  Object Load
         *  -----------
         */
	    public function __construct(){

	    	/**
	    	 *  Register : Post
	    	 *  ---------------
	    	 */
	    	add_action( 'init', [ $this, 'create_post_type' ], absint( '10' ) );

	    	/**
	    	 *  Register : Taxonomy
	    	 *  -------------------
	    	 */
	    	add_action( 'init', [ $this, 'create_taxonomy' ], absint( '10' ) );
	    }

    	/**
    	 *  1. Register : Post
    	 *  ------------------
    	 */
		public function create_post_type() {

			/**
			 *  Labels
			 *  ------
			 */
			$labels 						= 		[

				'name'                  	=> 		_x( 'Venues', 'Post Type General Name', 'sdweddingdirectory-venue' ),

				'singular_name'         	=> 		_x( 'Venue', 'Post Type Singular Name', 'sdweddingdirectory-venue' ),

				'menu_name'             	=> 		__( 'Venues', 'sdweddingdirectory-venue' ),

				'name_admin_bar'        	=> 		__( 'Venues', 'sdweddingdirectory-venue' ),

				'archives'              	=> 		__( 'Venue Archives', 'sdweddingdirectory-venue' ),

				'attributes'            	=> 		__( 'Venue Attributes', 'sdweddingdirectory-venue' ),

				'parent_item_colon'     	=> 		__( 'Venue Parent Item:', 'sdweddingdirectory-venue' ),

				'all_items'             	=> 		__( 'All Venues', 'sdweddingdirectory-venue' ),

				'add_new_item'          	=> 		__( 'Add New Venue', 'sdweddingdirectory-venue' ),

				'add_new'               	=> 		__( 'Add New Venue', 'sdweddingdirectory-venue' ),

				'new_item'              	=> 		__( 'New Venue', 'sdweddingdirectory-venue' ),

				'edit_item'             	=> 		__( 'Edit Venue', 'sdweddingdirectory-venue' ),

				'update_item'           	=> 		__( 'Update Venue', 'sdweddingdirectory-venue' ),

				'view_item'             	=> 		__( 'View Venue', 'sdweddingdirectory-venue' ),

				'view_items'            	=> 		__( 'View Venues', 'sdweddingdirectory-venue' ),

				'search_items'          	=> 		__( 'Search Venues', 'sdweddingdirectory-venue' ),

				'not_found'             	=> 		__( 'No venues found', 'sdweddingdirectory-venue' ),

				'not_found_in_trash'    	=> 		__( 'No venues found in Trash', 'sdweddingdirectory-venue' ),

				'featured_image'        	=> 		__( 'Featured Image for Venue', 'sdweddingdirectory-venue' ),

				'set_featured_image'    	=> 		__( 'Set featured image for Venue', 'sdweddingdirectory-venue' ),

				'remove_featured_image' 	=> 		__( 'Remove featured image for Venue', 'sdweddingdirectory-venue' ),

				'use_featured_image'    	=> 		__( 'Use as featured image for Venue', 'sdweddingdirectory-venue' ),

				'insert_into_item'      	=> 		__( 'Insert into Venue', 'sdweddingdirectory-venue' ),

				'uploaded_to_this_item' 	=> 		__( 'Uploaded to this Venue', 'sdweddingdirectory-venue' ),

				'items_list'            	=> 		__( 'Venues', 'sdweddingdirectory-venue' ),

				'items_list_navigation' 	=> 		__( 'Venue list navigation', 'sdweddingdirectory-venue' ),

				'filter_items_list'     	=> 		__( 'Filter Venues', 'sdweddingdirectory-venue' ),
			];

			$slug 						= 	self:: post_type();

			$slug_plural 				= 	$slug . 's';

			$rewrite 					= 	[

				'slug'                  => 	esc_attr( 'venues' ),

				'with_front'            => 	false,
			];

			/**
			 *  Args
			 *  ----
			 */
			$args 							= 		[

				'label'                 	=> 		__( 'Venues', 'sdweddingdirectory-venue' ),

				'description'           	=> 		__( 'Vendors can upload venues here.', 'sdweddingdirectory-venue' ),

				'labels'                	=> 		$labels,

				'supports'              	=> 		array( 'title', 'editor', 'thumbnail', 'author', 'excerpt' ),

				'taxonomies'            	=> 		[],

				'hierarchical'          	=> 		false,

				'public'                	=> 		true,

				'show_ui'               	=> 		true,

				'show_in_menu'          	=> 		true,

				'menu_position'         	=> 		self:: menu_position(),

				'menu_icon'             	=> 		esc_attr( 'dashicons-clipboard' ),

				'show_in_admin_bar'     	=> 		true,

				'show_in_nav_menus'     	=> 		true,

				'can_export'            	=> 		true,

				'has_archive'           	=> 		false,

				'exclude_from_search'   	=> 		true,

				'publicly_queryable'    	=> 		true,

				'capability_type'       	=> 		esc_attr( 'post' ),

				'map_meta_cap' 				=> 		true,

				'query_var' 				=> 		true,

				'rewrite' 					=> 		$rewrite,
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
                apply_filters( 'sdweddingdirectory/post-cap/' . self:: post_type(), false  )

                ?   [   'capabilities'      =>     array( 'create_posts' => 'do_not_allow' )  ]

                :   []

            ) );
		}

        /**
         *  Register Taxonomy
         *  -----------------
         */
        public static function create_taxonomy(){

	    	/**
	    	 *  Register / Taxonomy / Category
	    	 *  ------------------------------
	    	 */
			parent:: register_taxonomy( [

				'name'			=>		esc_attr__( 'Venue Type', 'sdweddingdirectory-venue' ),

				'slug'			=>		esc_attr( 'venue-type' ),

				'post_type'		=>		self:: post_type(),

				'rewrite'		=>		true,

				'rest_api'		=>		false,

			] );

	    	/**
	    	 *  Register / Taxonomy / Location
	    	 *  ------------------------------
	    	 */
			parent:: register_taxonomy( [

				'name'			=>		esc_attr__( 'Venue Location', 'sdweddingdirectory-venue' ),

				'slug'			=>		esc_attr( 'venue-location' ),

				'post_type'		=>		self:: post_type(),

				'rewrite'		=>		true,

				'rest_api'		=>		false,

			] );
		}
	}

    /**
     *  SDWeddingDirectory - Register Post
     *  --------------------------
     */
    SDWeddingDirectory_Register_Venue_Post::get_instance();	
}
