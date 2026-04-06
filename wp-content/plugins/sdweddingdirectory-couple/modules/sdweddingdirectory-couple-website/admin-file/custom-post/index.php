<?php
/**
 *  SDWeddingDirectory - Website Custom Post Type
 *  -------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Website_Register_Posts' ) && class_exists( 'SDWeddingDirectory_Register_Posts' ) ) {

	/**
	 *  SDWeddingDirectory - Website Custom Post Type
	 *  -------------------------------------
	 */
	class SDWeddingDirectory_Couple_Website_Register_Posts extends SDWeddingDirectory_Register_Posts{

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

        	return 		esc_attr( 'website' );
        }

        /**
         *  Menu Position
         *  -------------
         */
        public static function menu_position(){

        	return  	absint( '25' );
        }

        /**
         *  Object Start
         *  ------------
         */
	    public function __construct(){

	    	/**
	    	 *  Register : Post
	    	 *  ---------------
	    	 */
	    	add_action( 'init', [ $this, 'create_post_type' ], absint( '7' ) );

	    	/**
	    	 *  Register : Taxonomy
	    	 *  -------------------
	    	 */
	    	add_action( 'init', [ $this, 'create_taxonomy' ], absint( '7' ) );
	    }

    	/**
    	 *  1. Register Post
    	 *  ----------------
    	 */
		public function create_post_type() {

			/**
			 *  Labels
			 *  ------
			 */
			$labels 		= 		[

				'name'                  	=> 		_x( 'Website', 'Post Type General Name', 'sdweddingdirectory-couple-website' ),

				'singular_name'         	=> 		_x( 'Website', 'Post Type Singular Name', 'sdweddingdirectory-couple-website' ),

				'menu_name'             	=> 		__( 'Website', 'sdweddingdirectory-couple-website' ),

				'name_admin_bar'        	=> 		__( 'Website', 'sdweddingdirectory-couple-website' ),

				'archives'              	=> 		__( 'Website Archives', 'sdweddingdirectory-couple-website' ),

				'attributes'            	=> 		__( 'Website Attributes', 'sdweddingdirectory-couple-website' ),

				'parent_item_colon'     	=> 		__( 'Website Parent Item:', 'sdweddingdirectory-couple-website' ),

				'all_items'             	=> 		__( 'All Website', 'sdweddingdirectory-couple-website' ),

				'add_new_item'          	=> 		__( 'Add New Post', 'sdweddingdirectory-couple-website' ),

				'add_new'               	=> 		__( 'Add New Post', 'sdweddingdirectory-couple-website' ),

				'new_item'              	=> 		__( 'New Website', 'sdweddingdirectory-couple-website' ),

				'edit_item'             	=> 		__( 'Edit Website', 'sdweddingdirectory-couple-website' ),

				'update_item'           	=> 		__( 'Update Website', 'sdweddingdirectory-couple-website' ),

				'view_item'             	=> 		__( 'View Website', 'sdweddingdirectory-couple-website' ),

				'view_items'            	=> 		__( 'View Website', 'sdweddingdirectory-couple-website' ),

				'search_items'          	=> 		__( 'Search Website', 'sdweddingdirectory-couple-website' ),

				'not_found'             	=> 		__( 'Not found Website', 'sdweddingdirectory-couple-website' ),

				'not_found_in_trash'    	=> 		__( 'Not found in Trash Website', 'sdweddingdirectory-couple-website' ),

				'featured_image'        	=> 		__( 'Featured Image For Website', 'sdweddingdirectory-couple-website' ),

				'set_featured_image'    	=> 		__( 'Set featured image For Website', 'sdweddingdirectory-couple-website' ),

				'remove_featured_image' 	=> 		__( 'Remove featured image For Website', 'sdweddingdirectory-couple-website' ),

				'use_featured_image'    	=> 		__( 'Use as featured image In Website', 'sdweddingdirectory-couple-website' ),

				'insert_into_item'      	=> 		__( 'Insert into Website', 'sdweddingdirectory-couple-website' ),

				'uploaded_to_this_item' 	=> 		__( 'Uploaded to this Website', 'sdweddingdirectory-couple-website' ),

				'items_list'            	=> 		__( 'Website', 'sdweddingdirectory-couple-website' ),

				'items_list_navigation' 	=> 		__( 'Website list navigation', 'sdweddingdirectory-couple-website' ),

				'filter_items_list'     	=> 		__( 'Filter Website', 'sdweddingdirectory-couple-website' ),
			];

			$slug 						= 	self:: post_type();

			$slug_plural 				= 	$slug . 's';

			$rewrite 					= 	[

				'slug'                  => 	$slug,

				'with_front'            => 	true,
			];

			/**
			 *  Args
			 *  ----
			 */
			$args 			= 		[

				'label'                 	=> 		__( 'Website', 'sdweddingdirectory-couple-website' ),

				'description'           	=> 		__( 'Couple Can upload own wedding photoes, videoes and stories.', 'sdweddingdirectory-couple-website' ),

				'labels'                	=> 		$labels,

				'supports'              	=> 		array( 'title', 'editor', 'thumbnail', 'author' ),

				'taxonomies'            	=> 		[],

				'hierarchical'          	=> 		false,

				'public'                	=> 		true,

				'show_ui'               	=> 		true,

				'show_in_menu'          	=> 		true,

				'menu_position'         	=> 		self:: menu_position(),

				'menu_icon'             	=> 		esc_attr( 'dashicons-admin-site-alt3' ),

				'show_in_admin_bar'     	=> 		true,

				'show_in_nav_menus'     	=> 		true,

				'can_export'            	=> 		true,

				'has_archive'           	=> 		true,

				'exclude_from_search'   	=> 		true,

				'publicly_queryable'    	=> 		true,

				'capability_type'       	=> 		esc_attr( 'post' ),

				'map_meta_cap' 				=> 		true,

				'query_var' 				=> 		true,

				'rewrite' 					=> 		$rewrite
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
         *  Register Taxonomy
         *  -----------------
         */
        public static function create_taxonomy(){

	    	/**
	    	 *  Register / Taxonomy / Category
	    	 *  ------------------------------
	    	 */
			parent:: register_taxonomy( [

				'name'			=>		esc_attr__( 'Category', 'sdweddingdirectory-couple-website' ),

				'slug'			=>		esc_attr( self:: post_type() . '-category' ),

				'post_type'		=>		self:: post_type(),

				'rest_api'		=>		false,

			] );

	    	/**
	    	 *  Register / Taxonomy / Location
	    	 *  ------------------------------
	    	 */
			parent:: register_taxonomy( [

				'name'			=>		esc_attr__( 'Location', 'sdweddingdirectory-couple-website' ),

				'slug'			=>		esc_attr( self:: post_type() . '-location' ),

				'post_type'		=>		self:: post_type(),

				'rest_api'		=>		false,

			] );
		}
	}

	/**
	 *  SDWeddingDirectory - Website Custom Post Type
	 *  ------------------------------------------
	 */
    SDWeddingDirectory_Couple_Website_Register_Posts::get_instance();	
}