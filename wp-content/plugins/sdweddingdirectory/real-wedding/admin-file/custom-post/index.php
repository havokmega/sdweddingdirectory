<?php
/**
 *  SDWeddingDirectory - Real Wedding Custom Post Type
 *  ------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_RealWedding_Register_Posts' ) && class_exists( 'SDWeddingDirectory_Register_Posts' ) ) {

	/**
	 *  SDWeddingDirectory - Real Wedding Custom Post Type
	 *  ------------------------------------------
	 */
	class SDWeddingDirectory_RealWedding_Register_Posts extends SDWeddingDirectory_Register_Posts{

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

        	return 		esc_attr( 'real-wedding' );
        }

        /**
         *  Menu Position
         *  -------------
         */
        public static function menu_position(){

        	return  	absint( '25' );
        }

		/**
		 *  Object Loader
		 *  -------------
		 */
	    public function __construct(){

	    	/**
	    	 *  Register : Post
	    	 *  ---------------
	    	 */
	    	add_action( 'init', [ $this, 'create_post_type' ], absint( '6' ) );

	    	/**
	    	 *  Register : Taxonomy
	    	 *  -------------------
	    	 */
	    	add_action( 'init', [ $this, 'create_taxonomy' ], absint( '6' ) );

	    	/**
	    	 *  1. Create Column
	    	 *  ----------------
	    	 */
			add_filter( 'manage_edit-real-wedding-style_columns', [ $this, 'term_style_column' ], absint( '10' ), absint( '1' ) );

			/**
			 *  2. Column Value
			 *  ---------------
			 */
			add_filter( 'manage_real-wedding-style_custom_column', [ $this, 'term_style_column_content' ], absint( '10' ), absint( '3' ) );

	    	/**
	    	 *  1. Create Column
	    	 *  ----------------
	    	 */
			add_filter( 'manage_edit-real-wedding-season_columns', [ $this, 'term_season_column' ], absint( '10' ), absint( '1' ) );

			/**
			 *  2. Column Value
			 *  ---------------
			 */
			add_filter( 'manage_real-wedding-season_custom_column', [ $this, 'term_season_column_content' ], absint( '10' ), absint( '3' ) );

	    	/**
	    	 *  3. Create Column
	    	 *  ----------------
	    	 */
			add_filter( 'manage_edit-real-wedding-color_columns', [ $this, 'term_color_column' ], absint( '10' ), absint( '1' ) );

			/**
			 *  4. Column Value
			 *  ---------------
			 */
			add_filter( 'manage_real-wedding-color_custom_column', [ $this, 'term_color_column_content' ], absint( '10' ), absint( '3' ) );
	    }

    	/**
    	 *  1. Register Post
    	 *  ----------------
    	 */
		public function create_post_type() {

			$labels 	= 	[

				'name'                  =>  _x( 'Real Wedding', 'Post Type General Name', 'sdweddingdirectory-real-wedding' ),

				'singular_name'         =>  _x( 'Real Wedding', 'Post Type Singular Name', 'sdweddingdirectory-real-wedding' ),

				'menu_name'             =>  __( 'Real Wedding', 'sdweddingdirectory-real-wedding' ),

				'name_admin_bar'        =>  __( 'Real Wedding', 'sdweddingdirectory-real-wedding' ),

				'archives'              =>  __( 'Real Wedding Archives', 'sdweddingdirectory-real-wedding' ),

				'attributes'            =>  __( 'Real Wedding Attributes', 'sdweddingdirectory-real-wedding' ),

				'parent_item_colon'     =>  __( 'Real Wedding Parent Item:', 'sdweddingdirectory-real-wedding' ),

				'all_items'             =>  __( 'All Real Wedding', 'sdweddingdirectory-real-wedding' ),

				'add_new_item'          =>  __( 'Add New Post', 'sdweddingdirectory-real-wedding' ),

				'add_new'               =>  __( 'Add New Post', 'sdweddingdirectory-real-wedding' ),

				'new_item'              =>  __( 'New Real Wedding', 'sdweddingdirectory-real-wedding' ),

				'edit_item'             =>  __( 'Edit Real Wedding', 'sdweddingdirectory-real-wedding' ),

				'update_item'           =>  __( 'Update Real Wedding', 'sdweddingdirectory-real-wedding' ),

				'view_item'             =>  __( 'View Real Wedding', 'sdweddingdirectory-real-wedding' ),

				'view_items'            =>  __( 'View Real Wedding', 'sdweddingdirectory-real-wedding' ),

				'search_items'          =>  __( 'Search Real Wedding', 'sdweddingdirectory-real-wedding' ),

				'not_found'             =>  __( 'Not found Real Wedding', 'sdweddingdirectory-real-wedding' ),

				'not_found_in_trash'    =>  __( 'Not found in Trash Real Wedding', 'sdweddingdirectory-real-wedding' ),

				'featured_image'        =>  __( 'Featured Image For Real Wedding', 'sdweddingdirectory-real-wedding' ),

				'set_featured_image'    =>  __( 'Set featured image For Real Wedding', 'sdweddingdirectory-real-wedding' ),

				'remove_featured_image' =>  __( 'Remove featured image For Real Wedding', 'sdweddingdirectory-real-wedding' ),

				'use_featured_image'    =>  __( 'Use as featured image In Real Wedding', 'sdweddingdirectory-real-wedding' ),

				'insert_into_item'      =>  __( 'Insert into Real Wedding', 'sdweddingdirectory-real-wedding' ),

				'uploaded_to_this_item' =>  __( 'Uploaded to this Real Wedding', 'sdweddingdirectory-real-wedding' ),

				'items_list'            =>  __( 'Real Wedding', 'sdweddingdirectory-real-wedding' ),

				'items_list_navigation' =>  __( 'Real Wedding list navigation', 'sdweddingdirectory-real-wedding' ),

				'filter_items_list'     =>  __( 'Filter Real Wedding', 'sdweddingdirectory-real-wedding' ),
			];

			$slug 						= 	self:: post_type();

			$slug_plural 				= 	$slug . 's';

			$rewrite 					= 	[

				'slug'                  => 	esc_attr( 'real-weddings' ),

				'with_front'            => 	true,
			];

			/**
			 *  Args
			 *  ----
			 */
			$args 						=	[

				'label'                 => 	__( 'Real Wedding', 'sdweddingdirectory-real-wedding' ),

				'description'           => 	__( 'Register Couple Real Wedding Database Management Here', 'sdweddingdirectory-real-wedding' ),

				'labels'                => 	$labels,

				'supports'              => 	array( 'title', 'editor', 'thumbnail', 'author' ),

				'taxonomies'            => 	[],

				'hierarchical'          => 	false,

				'public'                => 	true,

				'show_ui'               => 	true,

				'show_in_menu'          => 	true,

				'menu_position'         => 	self:: menu_position(),

				'menu_icon'             => 	esc_attr( 'dashicons-format-gallery' ),

				'show_in_admin_bar'     => 	true,

				'show_in_nav_menus'     => 	true,

				'can_export'            => 	true,

				'has_archive'           => 	true,

				'exclude_from_search'   => 	true,

				'publicly_queryable'    => 	true,

				'capability_type'       => 	esc_attr( 'post' ),

				'map_meta_cap' 			=>  true,

				'query_var' 			=>  true,

				'rewrite' 				=>  $rewrite,
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
	    	 *  Register / Taxonomy / Location
	    	 *  ------------------------------
	    	 */
			parent:: register_taxonomy( [

				'name'			=>		esc_attr__( 'Location', 'sdweddingdirectory-real-wedding' ),

				'slug'			=>		esc_attr( self:: post_type() . '-location' ),

				'post_type'		=>		self:: post_type(),

				'rest_api'		=>		false,

			] );

	    	/**
	    	 *  Register / Taxonomy / Tags
	    	 *  --------------------------
	    	 */
			parent:: register_taxonomy( [

				'name'			=>		esc_attr__( 'Tag', 'sdweddingdirectory-real-wedding' ),

				'slug'			=>		esc_attr( self:: post_type() . '-tag' ),

				'post_type'		=>		self:: post_type(),

				'rest_api'		=>		false,

			] );

	    	// Removed taxonomies: space-preferance, color, community, season, style, category
	    	// These wedding-info taxonomies have been eliminated.
        }

        /**
         *  Term Column
         *  -----------
         */
		public static function term_season_column( $columns ){

		    $columns['icon'] = __( 'Icon', 'my_plugin' );

		    return $columns;
		}

        /**
         *  Term Column
         *  -----------
         */
		public static function term_style_column( $columns ){

		    $columns['icon'] = __( 'Icon', 'my_plugin' );

		    return $columns;
		}

        /**
         *  Term Column
         *  -----------
         */
		public static function term_color_column( $columns ){

		    $columns['color'] = __( 'Color', 'my_plugin' );

		    return $columns;
		}

		/**
		 *  Term Column Content
		 *  -------------------
		 */
		public static function term_season_column_content( $content, $column_name, $term_id ){

			/**
			 *  Is Icon Column ?
			 *  ----------------
			 */
		    if( $column_name !== 'icon' ){

		        return $content;
		    }

            /**
             *  Have Location Image ?
             *  ---------------------
             */
            $_icon     	=       sdwd_get_term_field(

                                    /**
                                     *  1. Term Key
                                     *  -----------
                                     */
                                    'icon',

                                    /**
                                     *  2. Term ID
                                     *  ----------
                                     */
                                    $term_id
                                );

		    if( ! empty( $_icon ) ){

		        $content 	.= 	esc_attr( $_icon );
		    }

		    else{

		    	$content 	.= 	esc_attr__( 'Icon Not Found!', 'sdweddingdirectory-real-wedding' );
		    }

		    /**
		     *  Return
		     *  ------
		     */
		    return 	$content;
		}

		/**
		 *  Term Column Content
		 *  -------------------
		 */
		public static function term_style_column_content( $content, $column_name, $term_id ){

			/**
			 *  Is Icon Column ?
			 *  ----------------
			 */
		    if( $column_name !== 'icon' ){

		        return $content;
		    }

            /**
             *  Have Location Image ?
             *  ---------------------
             */
            $_icon     	=       sdwd_get_term_field(

                                    /**
                                     *  1. Term Key
                                     *  -----------
                                     */
                                    'icon',

                                    /**
                                     *  2. Term ID
                                     *  ----------
                                     */
                                    $term_id
                                );

            /**
             *  Make sure icon not empty!
             *  -------------------------
             */
		    if( ! empty( $_icon ) ){
		        
		        $content 	.= 	esc_attr( $_icon );
		    }

		    else{

		    	$content 	.= 	esc_attr__( 'Icon Not Found!', 'sdweddingdirectory-real-wedding' );
		    }

		    /**
		     *  Return
		     *  ------
		     */
		    return 	$content;
		}

		/**
		 *  Term Column Content
		 *  -------------------
		 */
		public static function term_color_column_content( $content, $column_name, $term_id ){

			/**
			 *  Is Icon Column ?
			 *  ----------------
			 */
		    if( $column_name !== 'color' ){

		        return $content;
		    }

            /**
             *  Have Location Image ?
             *  ---------------------
             */
            $color     	=       sdwd_get_term_field(

                                    /**
                                     *  1. Term Key
                                     *  -----------
                                     */
                                    'color',

                                    /**
                                     *  2. Term ID
                                     *  ----------
                                     */
                                    $term_id
                                );

            /**
             *  Make sure color not empty!
             *  --------------------------
             */
		    if( ! empty( $color ) ){
		        
		        $content 	.= 	sprintf( '<span style="border: 10px solid %1$s;background: %1$s;border-radius: 20px;width: 10px;height: 10px;display: inline-block;"></span>', esc_attr( $color ) );
		    }

		    else{

		    	$content 	.= 	esc_attr__( 'Color Not Found!', 'sdweddingdirectory-real-wedding' );
		    }

		    /**
		     *  Return
		     *  ------
		     */
		    return 	$content;
		}
	}

	/**
	 *  SDWeddingDirectory - Real Wedding Custom Post Type
	 *  ------------------------------------------
	 */
    SDWeddingDirectory_RealWedding_Register_Posts::get_instance();	
}
