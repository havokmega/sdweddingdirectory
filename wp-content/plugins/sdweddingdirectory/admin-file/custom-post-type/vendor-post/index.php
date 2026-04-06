<?php
/**
 *  SDWeddingDirectory - Register Post and Taxonomy
 *  ---------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Register_Vendor_Posts' ) && class_exists( 'SDWeddingDirectory_Register_Posts' ) ) {

	/**
	 *  SDWeddingDirectory - Register Post and Taxonomy
	 *  ---------------------------------------
	 */
	class SDWeddingDirectory_Register_Vendor_Posts extends SDWeddingDirectory_Register_Posts {

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

        	return 		esc_attr( 'vendor' );
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
	    	add_action( 'init', [ $this, 'create_post_type' ], absint( '8' ) );

	    	/**
	    	 *  Register : Taxonomy
	    	 *  -------------------
	    	 */
	    	add_action( 'init', [ $this, 'create_taxonomy' ], absint( '8' ) );

		    /**
		     *  Term Meta - Header
		     *  ------------------
		     */
	    	add_filter( 'sdweddingdirectory/tax-meta/header-setting', [ $this, 'term_header_metabox' ], absint( '20' ), absint( '1' ) );

	    	/**
	    	 *  Add Marker Setting Meta
	    	 *  -----------------------
	    	 */
	    	add_filter( 'sdweddingdirectory/tax-meta/marker-setting', [ $this, 'term_marker_metabox' ], absint( '20' ), absint( '1' ) );

		    /**
		     *  Term Meta - Image
		     *  -----------------
		     */
	    	add_filter( 'sdweddingdirectory/tax-meta/media', [ $this, 'term_media_metabox' ], absint( '20' ), absint( '1' ) );

		    /**
		     *  Term Meta - Icon
		     *  ----------------
		     */
	    	add_filter( 'sdweddingdirectory/tax-meta/icon', [ $this, 'term_icon_metabox' ], absint( '20' ), absint( '1' ) );
	    }

    	/**
    	 *  2. Register : Vendor Post
    	 *  -------------------------
    	 */
		public function create_post_type() {

			/**
			 *  Labels
			 *  ------
			 */
			$labels 	= 		[

				'name'                  	=> 		_x( 'Vendor', 'Post Type General Name', 'sdweddingdirectory' ),

				'singular_name'         	=> 		_x( 'Vendor', 'Post Type Singular Name', 'sdweddingdirectory' ),

				'menu_name'             	=> 		__( 'Vendor', 'sdweddingdirectory' ),

				'name_admin_bar'        	=> 		__( 'Vendor', 'sdweddingdirectory' ),

				'archives'              	=> 		__( 'Vendor Archives', 'sdweddingdirectory' ),

				'attributes'            	=> 		__( 'Vendor Attributes', 'sdweddingdirectory' ),

				'parent_item_colon'     	=> 		__( 'Vendor Parent Item:', 'sdweddingdirectory' ),

				'all_items'             	=> 		__( 'All Vendor', 'sdweddingdirectory' ),

				'add_new_item'          	=> 		__( 'Add New Vendor', 'sdweddingdirectory' ),

				'add_new'               	=> 		__( 'Add New Vendor', 'sdweddingdirectory' ),

				'new_item'              	=> 		__( 'New Item Vendor', 'sdweddingdirectory' ),

				'edit_item'             	=> 		__( 'Edit Vendor', 'sdweddingdirectory' ),

				'update_item'           	=> 		__( 'Update Vendor', 'sdweddingdirectory' ),

				'view_item'             	=> 		__( 'View Vendor', 'sdweddingdirectory' ),

				'view_items'            	=> 		__( 'View Vendor', 'sdweddingdirectory' ),

				'search_items'          	=> 		__( 'Search Vendor', 'sdweddingdirectory' ),

				'not_found'             	=> 		__( 'Not found Vendor', 'sdweddingdirectory' ),

				'not_found_in_trash'    	=> 		__( 'Not found in Trash Vendor', 'sdweddingdirectory' ),

				'featured_image'        	=> 		__( 'Featured Image For Vendor', 'sdweddingdirectory' ),

				'set_featured_image'    	=> 		__( 'Set featured image For Vendor', 'sdweddingdirectory' ),

				'remove_featured_image' 	=> 		__( 'Remove featured image For Vendor', 'sdweddingdirectory' ),

				'use_featured_image'    	=> 		__( 'Use as featured image In Vendor', 'sdweddingdirectory' ),

				'insert_into_item'      	=> 		__( 'Insert into Vendor', 'sdweddingdirectory' ),

				'uploaded_to_this_item' 	=> 		__( 'Uploaded to this Vendor', 'sdweddingdirectory' ),

				'items_list'            	=> 		__( 'Vendor', 'sdweddingdirectory' ),

				'items_list_navigation' 	=> 		__( 'Vendor list navigation', 'sdweddingdirectory' ),

				'filter_items_list'     	=> 		__( 'Filter Vendor', 'sdweddingdirectory' ),
			];

			$slug 						= 	self:: post_type();

			$slug_plural 				= 	$slug . 's';

			$rewrite 					= 	[

				'slug'                  => 	$slug,

				'with_front'            => 	false,
			];

			/**
			 *  Args
			 *  ----
			 */
			$args 		= 		[

				'label'                 	=>		__( 'Vendor', 'sdweddingdirectory' ),

				'description'           	=>		__( 'Register Vendor User Database Management Here', 'sdweddingdirectory' ),

				'labels'                	=>		$labels,

				'supports'              	=>		array( 'title', 'editor', 'thumbnail' ),

				'taxonomies'            	=>		[],

				'hierarchical'          	=>		false,

				'public'                	=>		true,

				'show_ui'               	=>		true,

				'show_in_menu'          	=>		true,

				'menu_position'         	=> 		self:: menu_position(),

				'menu_icon'             	=>		esc_attr( 'dashicons-store' ),

				'show_in_admin_bar'     	=>		true,

				'show_in_nav_menus'     	=>		true,

				'can_export'            	=>		true,

				'has_archive'           	=>		true,

				'exclude_from_search'   	=>		true,

				'publicly_queryable'    	=>		true,

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

				'name'			=>		esc_attr__( 'Category', 'sdweddingdirectory' ),

				'slug'			=>		esc_attr( self:: post_type() . '-category' ),

				'post_type'		=>		self:: post_type(),

				'rest_api'		=>		false,

			] );

		}

	    /**
	     *  Term Meta - Header
	     *  ------------------
	     */
	    public static function term_header_metabox( $args = [] ){

	    	return   	array_merge( $args, [

	    					[	
	    						'taxonomy'		=>		esc_attr( 'vendor-category' ),

	    						'rand_id'		=>		esc_attr( 'k8fxaucdsj' )
	    					],

	    				] );
	    }

	    /**
	     *  Term Meta - Image
	     *  -----------------
	     */
	    public static function term_media_metabox( $args = [] ){

	    	return   	array_merge( $args, [

	    					[	
	    						'taxonomy'		=>		esc_attr( 'vendor-category' ),

	    						'rand_id'		=>		esc_attr( 'mdpjrvftgh' )
	    					],

	    				] );
	    }

	    /**
	     *  Term Meta - Icon
	     *  ----------------
	     */
	    public static function term_icon_metabox( $args = [] ){

	    	return   	array_merge( $args, [

	    					[	

	    						'taxonomy'		=>		esc_attr( 'vendor-category' ),

	    						'rand_id'		=>		esc_attr( 'wwrqunifzk' )
	    					],

	    				] );
	    }

	    /**
	     *  Marker Meta
	     *  -----------
	     */
	    public static function term_marker_metabox( $args = [] ){

	    	return   	array_merge( $args, [

	    					[	
	    						'taxonomy'		=>		esc_attr( 'vendor-category' ),

	    						'rand_id'		=>		esc_attr( 'mp8vnpjx9y' )
	    					],

	    				] );
	    }
	}

	/**
	 *  SDWeddingDirectory - Register Post and Taxonomy
	 *  ---------------------------------------
	 */
    SDWeddingDirectory_Register_Vendor_Posts::get_instance();	
}
