<?php
/**
 *  SDWeddingDirectory - Register Post and Taxonomy
 *  ---------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Register_Couple_Posts' ) && class_exists( 'SDWeddingDirectory_Register_Posts' ) ) {

	/**
	 *  SDWeddingDirectory - Register Post and Taxonomy
	 *  ---------------------------------------
	 */
	class SDWeddingDirectory_Register_Couple_Posts extends SDWeddingDirectory_Register_Posts {

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

        	return 		esc_attr( 'couple' );
        }

        /**
         *  Menu Position
         *  -------------
         */
        public static function menu_position(){

        	return  	absint( '25' );
        }

        /**
         *  Load Object
         *  -----------
         */
	    public function __construct(){

	    	/**
	    	 *  Register : Post
	    	 *  ---------------
	    	 */
	    	add_action( 'init', [ $this, 'create_post_type' ], absint( '5' ) );

	    	/**
	    	 *  Couple taxonomy registration removed
	    	 *  ------------------------------------
	    	 */
	    }

    	/**
    	 *  1. Register : Couple Post
    	 *  -------------------------
    	 */
		public static function create_post_type() {

			/**
			 *  Labels
			 *  ------
			 */
			$labels 		= 	[

				'name'                  	=> 		_x( 'Couple', 'Post Type General Name', 'sdweddingdirectory' ),

				'singular_name'         	=> 		_x( 'Couple', 'Post Type Singular Name', 'sdweddingdirectory' ),

				'menu_name'             	=> 		__( 'Couple', 'sdweddingdirectory' ),

				'name_admin_bar'        	=> 		__( 'Couple', 'sdweddingdirectory' ),

				'archives'              	=> 		__( 'Couple Archives', 'sdweddingdirectory' ),

				'attributes'            	=> 		__( 'Couple Attributes', 'sdweddingdirectory' ),

				'parent_item_colon'     	=> 		__( 'Couple Parent Item:', 'sdweddingdirectory' ),

				'all_items'             	=> 		__( 'All Couple', 'sdweddingdirectory' ),

				'add_new_item'          	=> 		__( 'Add New Couple', 'sdweddingdirectory' ),

				'add_new'               	=> 		__( 'Add New Couple', 'sdweddingdirectory' ),

				'new_item'              	=> 		__( 'New Item Couple', 'sdweddingdirectory' ),

				'edit_item'             	=> 		__( 'Edit Couple', 'sdweddingdirectory' ),

				'update_item'           	=> 		__( 'Update Couple', 'sdweddingdirectory' ),

				'view_item'             	=> 		__( 'View Couple', 'sdweddingdirectory' ),

				'view_items'            	=> 		__( 'View Couple', 'sdweddingdirectory' ),

				'search_items'          	=> 		__( 'Search Couple', 'sdweddingdirectory' ),

				'not_found'             	=> 		__( 'Not found Couple', 'sdweddingdirectory' ),

				'not_found_in_trash'    	=> 		__( 'Not found in Trash Couple', 'sdweddingdirectory' ),

				'featured_image'        	=> 		__( 'Featured Image For Couple', 'sdweddingdirectory' ),

				'set_featured_image'    	=> 		__( 'Set featured image For Couple', 'sdweddingdirectory' ),

				'remove_featured_image' 	=> 		__( 'Remove featured image For Couple', 'sdweddingdirectory' ),

				'use_featured_image'    	=> 		__( 'Use as featured image In Couple', 'sdweddingdirectory' ),

				'insert_into_item'      	=> 		__( 'Insert into Couple', 'sdweddingdirectory' ),

				'uploaded_to_this_item' 	=> 		__( 'Uploaded to this Couple', 'sdweddingdirectory' ),

				'items_list'            	=> 		__( 'Couple', 'sdweddingdirectory' ),

				'items_list_navigation' 	=> 		__( 'Couple list navigation', 'sdweddingdirectory' ),

				'filter_items_list'     	=> 		__( 'Filter Couple', 'sdweddingdirectory' ),
			];

			$slug 						= 	self:: post_type();

			$slug_plural 				= 	$slug . 's';

			$rewrite 					= 	[

				'slug'                  => 	$slug,

				'with_front'            => 	true,
			];

			/**
			 *  Have Args ?
			 *  -----------
			 */
			$args 	=	 [

				'label'                 	=>	 	__( 'Couple', 'sdweddingdirectory' ),

				'description'           	=>	 	__( 'Register Couple User Database Management Here', 'sdweddingdirectory' ),

				'labels'                	=>	 	$labels,

				'supports'              	=>	 	array( 'title', 'editor', 'thumbnail' ),

				'taxonomies'            	=>		[],

				'hierarchical'          	=> 		false,

				'public'                	=> 		true,

				'show_ui'               	=> 		true,

				'show_in_menu'          	=> 		true,

				'menu_position'         	=> 		self:: menu_position(),

				'menu_icon'             	=> 		esc_attr( 'dashicons-heart' ),

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

	}

	/**
	 *  SDWeddingDirectory - Register Post and Taxonomy
	 *  ---------------------------------------
	 */
    SDWeddingDirectory_Register_Couple_Posts::get_instance();	
}
